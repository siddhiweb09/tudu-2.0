<?php
// app/Http/Controllers/TaskController.php
namespace App\Http\Controllers;

use App\Models\DelegatedTask;
use Illuminate\Support\Facades\Validator;
use App\Models\Task;
use App\Models\TaskComment;
use App\Models\TaskList;
use App\Models\TaskMedia;
use App\Models\TaskLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\TelegramWebhookMessage;
use App\Models\TaskSchedule;
use App\Services\WhatsAppService;
use App\Models\TelegramWhatsappLog;

use Carbon\Carbon;

class TaskController extends Controller
{

    protected $whatsapp;

    public function __construct(WhatsAppService $whatsapp)
    {
        $this->whatsapp = $whatsapp;
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $activeUser = $user->employee_code . "*" . $user->employee_name;

        // Validate the main task data
        $validator = Validator::make($request->all(), [
            'task_title' => 'required|string|max:255',
            'task_description' => 'required|string',
            'final_project_name' => 'required|string',
            'category' => 'required|string|max:255',
            'assign_to' => 'required|string',
            'due_date' => 'nullable|string',
            'priority' => 'required|in:high,medium,low',
            'tasks_json' => 'json',
            // 'voice_notes' => 'json',
            'voice_notes' => 'nullable|array',
            'voice_notes.*' => 'file|max:10240',
            'documents' => 'nullable|array',
            'documents.*' => 'file|max:10240', // 10MB max
            'links_json' => 'json',
            'reminders_json' => 'json',
            'frequency' => 'required_if:recurring,on',
            'frequency_duration_json' => 'json',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'messages' => $validator->errors()
            ], 422);
        }

        // Create the task
        $task = Task::create([
            'title' => $request->task_title,
            'description' => $request->task_description,
            'project_name' => $request->final_project_name,
            'task_list' => $request->tasks_json,
            'department' => $request->category,
            'due_date' => $request->due_date,
            'priority' => $request->priority,
            'is_recurring' => $request->has('recurring') ? true : false,
            'frequency' => $request->frequency,
            'frequency_duration' => $request->frequency_duration_json,
            'reminders' => $request->reminders_json,
            'links' => $request->links_json,
            'assign_to' => $request->assign_to,
            'status' => 'Pending',
            'final_status' => 'Pending',
            'assign_by' => $activeUser,
        ]);

        // Log task creation
        TaskLog::create([
            'task_id' => $task->task_id,
            'log_description' => 'Task created',
            'added_by' => $activeUser
        ]);

        TaskSchedule::create([
            'task_id' => $task->task_id,
            'assigned_date' => Carbon::now('Asia/Kolkata'),
            'status' => 'Pending',
            'frequency' => $request->frequency,
            'created_by' => $activeUser,
            'updated_by' => $activeUser,
        ]);

        // Handle file uploads
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $document) {
                $fileName = $this->storeFile($document);

                TaskMedia::create([
                    'task_id' => $task->task_id,
                    'category' => 'document',
                    'file_name' => $fileName,
                    'created_by' => $activeUser
                ]);
            }
        }

        // Handle voice notes from JSON
        if ($request->hasFile('voice_notes')) {
            foreach ($request->file('voice_notes') as $voiceNote) {
                if ($voiceNote->isValid()) {
                    $fileName = 'voice_note_' . time() . '_' . uniqid() . '.wav';
                    $voiceNote->move(public_path('assets/uploads'), $fileName);
                    // ... create record ...
                    TaskMedia::create([
                        'task_id' => $task->task_id,
                        'category' => 'voice_note',
                        'file_name' => $fileName,
                        'created_by' => $activeUser
                    ]);
                }
            }
        }
        // Handle links
        $links = json_decode($request->links_json, true) ?? [];
        foreach ($links as $link) {
            if (!empty($link)) {
                TaskMedia::create([
                    'task_id' => $task->task_id,
                    'category' => 'link',
                    'file_name' => $link,
                    'created_by' => $activeUser
                ]);
            }
        }

        // Handle Tasks
        $task_list = json_decode($request->tasks_json, true) ?? [];
        foreach ($task_list as $taskItem) {
            if (!empty($taskItem)) {
                TaskList::create([
                    'task_id' => $task->task_id,
                    'tasks' => $taskItem,
                    'assign_to' => $request->assign_to,
                    'assign_by' => $activeUser,
                    'updated_by' => $activeUser
                ]);
            }
        }

        try {
            $telegramConfig = DB::table('telegram_rn')->where('name', 'task')->first();
            $botId = $telegramConfig->botId ?? null;

            if (!$botId) {
                Log::error('Telegram Bot ID not found for task notification.');
                return;
            }

            $assign_to_parts = explode('*', $request->assign_to);
            $assign_to_employee_code = trim($assign_to_parts[0]);

            $assignChatId = DB::table('users')->where('employee_code', $assign_to_employee_code)->value('telegram_chat_id');

            if ($assignChatId) {
                $message = "📢 <b>New Task Assigned</b>\n\n";
                $message .= "📌 <b>Title:</b> " . e($request->task_title) . "\n";
                $message .= "⚠️ <b>Priority:</b> " . e($request->priority) . "\n";
                $message .= "⏰ <b>Due Date:</b> " . ($request->due_date ?: 'Not Set') . "\n";
                $message .= "👤 <b>Assigned To:</b> " . e($request->assign_to) . "\n";
                $message .= "🧑‍💼 <b>Assigned By:</b> " . e($user->employee_name) . "\n";

                $inlineKeyboard = [
                    'inline_keyboard' => [
                        [
                            ['text' => '📂 Open Task!', 'url' => url('task_detail.php')]
                        ]
                    ]
                ];

                $response = Http::post("https://api.telegram.org/bot{$botId}/sendMessage", [
                    'chat_id' => $assignChatId,
                    'text' => $message,
                    'parse_mode' => 'HTML',
                    'reply_markup' => json_encode($inlineKeyboard)
                ]);

                $responseData = $response->json();
                Log::info('Telegram Response:', $responseData);

                if (!empty($responseData['ok']) && !empty($responseData['result'])) {
                    TelegramWebhookMessage::create([
                        'chat_id' => $assignChatId,
                        'message_id' => $responseData['result']['message_id'],
                        'name' => Auth::guard('web')->check() ? Auth::guard('web')->user()->employee_name : null,
                        'username' => 'TelegramBot',
                        'message_text' => $message,
                        'message_array_data' => json_encode($responseData),
                        'messageDate' => Carbon::now('Asia/Kolkata'),
                        'created_at' => Carbon::now('Asia/Kolkata'),
                        'updated_at' => Carbon::now('Asia/Kolkata')
                    ]);

                    TaskLog::create([
                        'task_id' => $request->task_id,
                        'log_description' => 'Task created Notification Send on Telegram',
                        'added_by' => $activeUser
                    ]);

                    TelegramWhatsappLog::create([
                        'task_id' => $request->task_id,
                        'log_description' => 'Task created Notification Send on Telegram',
                        'notification_on' => 'Telegram'
                    ]);
                } else {
                    Log::warning("Telegram message not sent. Reason: " . ($responseData['description'] ?? 'Unknown'));

                    TaskLog::create([
                        'task_id' => $request->task_id,
                        'log_description' => 'Task created Notification Not Send on Telegram Chat ID Not Register',
                        'added_by' => $activeUser
                    ]);
                }
            } else {
                Log::warning("No Telegram chat ID found for employee code: {$assign_to_employee_code}");
            }

            $assign_to_employee_code = trim($assign_to_parts[0]);
            $assignToMobileNoPersonal = DB::table('users')->where('employee_code', $assign_to_employee_code)->value('mobile_no_personal');

            if ($assignToMobileNoPersonal) {
                $waMessage = "📢 *New Task Assigned*\n\n";
                $waMessage .= "📌 Title: " . e($request->task_title) . "\n";
                $waMessage .= "⚠️ Priority: " . e($request->priority) . "\n";
                $waMessage .= "⏰ Due Date: " . ($request->due_date ?: 'Not Set') . "\n";
                $waMessage .= "👤 Assigned To: " . e($request->assign_to) . "\n";
                $waMessage .= "🧑‍💼 Assigned By: " . e($user->employee_name) . "\n";

                $response = $this->whatsapp->sendWaMessage(
                    '169617539573196',
                    'EAAEY6g6mDSUBO4a9RKn5hBaFTL4YbYxaA3LlDlMKHNLWVqnTQpHXWw6V8Ta6P7SRg7dJeaaDnsXyRKNHKZCdXcbZCmP4KgAZAwDCAdxUnBgwXx0nW79EyPpUhkuBLN8BWkVnrmsrmZB7yqQHtxONqmzCASGTW6AJTaeZAoZAiC09etCHMnL4ZBV7GnnWqEwdZBzo',
                    $assignToMobileNoPersonal,
                    'text',
                    ['body' => $waMessage],
                    '',
                    '',
                    ''
                );

                TaskLog::create([
                    'task_id' => $request->task_id,
                    'log_description' => 'Task created Notification Send on Whatsapp',
                    'added_by' => $activeUser
                ]);

                TelegramWhatsappLog::create([
                    'task_id' => $request->task_id,
                    'log_description' => 'Task created Notification Send on Telegram',
                    'notification_on' => 'Whatsapp'
                ]);
            } else {
                TaskLog::create([
                    'task_id' => $request->task_id,
                    'log_description' => 'Task created Notification Not Send on Whatsapp Mobile No not Register',
                    'added_by' => $activeUser
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Telegram Notification Failed: ' . $e->getMessage());
        }


        return response()->json([
            'status' => 'success',
            'message' => 'Task created successfully!',
            'task_id' => $task->task_id
        ]);
    }

    private function storeFile($file)
    {
        // Create uploads directory if it doesn't exist
        $uploadPath = public_path('assets/uploads');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        // Generate unique filename
        $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();

        // Move file to public/assets/uploads
        $file->move($uploadPath, $fileName);

        return $fileName;
    }

    public function allTask()
    {
        return view('tasks.allTasks');
    }


    public function getTasksByType($type)
    {
        $user = Auth::user();
        $usercode = $user->employee_code . '*' . $user->employee_name;
        $today = Carbon::now('Asia/Kolkata');

        // Initialize query conditions based on task type
        $conditions = [];
        $finalStatusConditions = [];
        $dateCondition = null;

        switch ($type) {
            case 'pending':
                $conditions['status'] = 'Pending';
                break;
            case 'in-process':
                $conditions['status'] = 'In Process';
                break;
            case 'in-review':
                $finalStatusConditions['final_status'] = 'Pending';
                break;
            case 'overdue':
                $dateCondition = ['due_date', '<', $today];
                break;
            case 'all':
                // No conditions - get all tasks
                break;
            default:
                return response()->json(['error' => 'Invalid task type'], 400);
        }

        // Step 1: Get main tasks
        $mainQuery = Task::where(function ($query) use ($usercode) {
            $query->where('assign_to', $usercode)
                ->orWhere('assign_by', $usercode);
        });

        if (!empty($conditions)) {
            $mainQuery->where($conditions);
        }
        if (!empty($finalStatusConditions)) {
            $mainQuery->where($finalStatusConditions);
        }
        if (isset($dateCondition)) {
            $mainQuery->whereDate(...$dateCondition);
        }

        $mainTasks = $mainQuery->get()->each(function ($task) {
            $task->flag = 'Main';
        });

        // Step 2: Get delegated tasks
        $delegatedQuery = DelegatedTask::where(function ($query) use ($usercode) {
            $query->where('assign_to', $usercode)
                ->orWhere('assign_by', $usercode);
        })->where(function ($query) use ($usercode) {
            $query->whereNull('not_visible_to')
                ->orWhereJsonDoesntContain('not_visible_to', $usercode);
        });

        if (!empty($conditions)) {
            $delegatedQuery->where($conditions);
        }
        if (!empty($finalStatusConditions)) {
            $delegatedQuery->where($finalStatusConditions);
        }
        if (isset($dateCondition)) {
            $delegatedQuery->whereDate(...$dateCondition);
        }

        $directDelegatedTasks = $delegatedQuery->get()->each(function ($task) {
            $task->flag = 'Delegated';
        });

        // Step 3: Get related delegated tasks
        $mainTaskIds = $mainTasks->pluck('task_id')->toArray();

        $relatedDelegatedQuery = DelegatedTask::whereIn('task_id', $mainTaskIds)
            ->where(function ($query) use ($usercode) {
                $query->whereNull('not_visible_to')
                    ->orWhereJsonDoesntContain('not_visible_to', $usercode);
            });

        if (!empty($conditions)) {
            $relatedDelegatedQuery->where($conditions);
        }
        if (!empty($finalStatusConditions)) {
            $relatedDelegatedQuery->where($finalStatusConditions);
        }
        if (isset($dateCondition)) {
            $relatedDelegatedQuery->whereDate(...$dateCondition);
        }

        $relatedDelegatedTasks = $relatedDelegatedQuery->get()->each(function ($task) {
            $task->flag = 'Delegated';
        });

        // Step 4: Merge all tasks
        $allTasks = $mainTasks->merge($directDelegatedTasks)->merge($relatedDelegatedTasks);

        // Step 5: Group by priority
        $organizedTasks = $allTasks->groupBy(function ($task) {
            return ucfirst(strtolower($task->priority ?? 'low'));
        });

        $projectCount = $allTasks->pluck('project_name')
            ->filter()
            ->unique()
            ->count();

        return response()->json([
            'tasksByPriority' => $organizedTasks,
            'totalTasks' => $allTasks->count(),
            'projectCount' => $projectCount,
            'type' => $type
        ]);
    }

    public function getUserTasks($id)
    {
        $proUser = User::findOrFail($id);
        $usercode = $proUser->employee_code . '*' . $proUser->employee_name;

        // Step 1: Get all main tasks
        $mainTasks = Task::where(function ($query) use ($usercode) {
            $query->where('assign_to', $usercode)
                ->orWhere('assign_by', $usercode);
        })->get()->each(function ($task) {
            $task->flag = 'Main';
        });

        // Step 2: Get direct delegated tasks
        $directDelegatedTasks = DelegatedTask::where(function ($query) use ($usercode) {
            $query->where('assign_to', $usercode)
                ->orWhere('assign_by', $usercode);
        })
            ->where(function ($query) use ($usercode) {
                $query->whereNull('not_visible_to')
                    ->orWhereJsonDoesntContain('not_visible_to', $usercode);
            })
            ->get()
            ->each(function ($task) {
                $task->flag = 'Delegated';
            });

        // Step 3: Get related delegated tasks
        $mainTaskIds = $mainTasks->pluck('task_id')->toArray();
        $relatedDelegatedTasks = DelegatedTask::whereIn('task_id', $mainTaskIds)
            ->where(function ($query) use ($usercode) {
                $query->whereNull('not_visible_to')
                    ->orWhereJsonDoesntContain('not_visible_to', $usercode);
            })
            ->get()
            ->each(function ($task) {
                $task->flag = 'Delegated';
            });

        // Step 4: Merge all tasks
        $allTasks = $mainTasks->merge($directDelegatedTasks)->merge($relatedDelegatedTasks);

        // Step 5: Calculate completion metrics
        $completedTasks = $allTasks->filter(function ($task) {
            return strtolower($task->final_status ?? '') === 'completed';
        });

        $totalTasksCount = $allTasks->count();
        $completedTasksCount = $completedTasks->count();
        $completionPercentage = $totalTasksCount > 0
            ? round(($completedTasksCount / $totalTasksCount) * 100)
            : 0;

        // Step 6: Count distinct projects
        $projectCount = $allTasks->pluck('project_name')
            ->filter()
            ->unique()
            ->count();

        // Step 7: Group tasks by status for detailed breakdown
        $tasksByStatus = $allTasks->groupBy(function ($task) {
            if (strtolower($task->final_status ?? '') === 'completed') {
                return 'completed';
            }
            return strtolower($task->status ?? 'pending');
        })->map(function ($tasks) {
            return $tasks->count();
        });

        // Step 8: Group tasks by priority
        $tasksByPriority = $allTasks->groupBy(function ($task) {
            return ucfirst(strtolower($task->priority ?? 'low'));
        })->map(function ($tasks) {
            return $tasks->count();
        });

        $tasksByProject = $allTasks->filter(fn($task) => !empty($task->project_name))
            ->groupBy('project_name')
            ->map->count();

        return response()->json([
            'user' => [
                'id' => $proUser->id,
            ],
            'metrics' => [
                'total_tasks' => $totalTasksCount,
                'completed_tasks' => $completedTasksCount,
                'completion_percentage' => $completionPercentage,
                'total_projects' => $projectCount,
                'overdue_tasks' => $allTasks->filter(fn($t) => $t->due_date && Carbon::parse($t->due_date)->isPast())->count(),
            ],
            'breakdown' => [
                'by_status' => $tasksByStatus,
                'by_priority' => $tasksByPriority,
                'by_project' => $tasksByProject,
            ],
            'all_tasks' => $allTasks->map(function ($task) {
                // Common fields for both Task and DelegatedTask
                $baseFields = [
                    'id' => $task->flag === 'Main' ? $task->task_id : $task->delegate_task_id,
                    'type' => $task->flag,
                    'title' => $task->title,
                    'status' => $task->status,
                    'final_status' => $task->final_status,
                    'priority' => $task->priority,
                    'project_name' => $task->project_name,
                    'due_date' => $task->due_date,
                    'frequency' => $task->frequency,
                    'frequency_duration' => $task->frequency_duration,
                    'assign_by' => $task->assign_by,
                    'assign_to' => $task->assign_to,
                    'created_at' => $task->created_at,
                    'updated_at' => $task->updated_at,
                ];

                // Add model-specific fields
                if ($task->flag === 'Main') {
                    $baseFields += [
                        'task_id' => $task->task_id,
                        'start_date' => $task->start_date,
                        'end_date' => $task->end_date,
                        'dependencies' => $task->dependencies,
                        'attachments' => $task->attachments,
                        // Include all other Task model fields
                    ];
                } else {
                    $baseFields += [
                        'delegate_task_id' => $task->delegate_task_id,
                        'task_id' => $task->task_id,
                        'not_visible_to' => $task->not_visible_to,
                        'delegation_notes' => $task->delegation_notes,
                        // Include all other DelegatedTask model fields
                    ];
                }

                return $baseFields;
            })
        ]);
    }

    public function pendingTask()
    {
        return view('tasks.pendingTask');
    }

    public function inProcessTask()
    {
        return view('tasks.inProcessTask');
    }

    public function inReviewTask()
    {
        return view('tasks.inReviewTask');
    }

    public function overdueTask()
    {
        return view('tasks.overdueTask');
    }

    public function delegateTask($id)
    {
        return view('tasks.delegate', ['taskId' => $id]);
    }

    public function storeDelegateForm(Request $request)
    {
        $user = Auth::user();
        $activeUser = $user->employee_code . "*" . $user->employee_name;

        // Validate the main task data
        $validator = Validator::make($request->all(), [
            'task_id' => 'required|string',
            'delegate_task_title' => 'required|string|max:255',
            'task_description' => 'required|string',
            'category' => 'required|string|max:255',
            'assign_to' => 'required|string',
            'due_date' => 'nullable|string',
            'priority' => 'required|in:high,medium,low',
            'tasks_json' => 'json',
            // 'voice_notes' => 'json',
            'voice_notes' => 'nullable|array',
            'voice_notes.*' => 'file|max:10240',
            'documents' => 'nullable|array',
            'documents.*' => 'file|max:10240', // 10MB max
            'links_json' => 'json',
            'reminders_json' => 'json',
            'frequency' => 'required_if:recurring,on',
            'frequency_duration_json' => 'json',
            'visible_json' => 'json',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'messages' => $validator->errors()
            ], 422);
        }

        // Create the task
        $task = DelegatedTask::create([
            'task_id' => $request->task_id,
            'title' => $request->delegate_task_title,
            'description' => $request->task_description,
            'department' => $request->category,
            'due_date' => $request->due_date,
            'priority' => $request->priority,
            'is_recurring' => $request->has('recurring') ? true : false,
            'frequency' => $request->frequency,
            'frequency_duration' => $request->frequency_duration_json,
            'reminders' => $request->reminders_json,
            'links' => $request->links_json,
            'assign_to' => $request->assign_to,
            'status' => 'Pending',
            'final_status' => 'Pending',
            'not_visible_to' => $request->visible_json,
            'assign_by' => $activeUser,
        ]);

        // Log task creation
        TaskLog::create([
            'task_id' => $task->delegate_task_id,
            'log_description' => 'Task delegated',
            'added_by' => $activeUser
        ]);

        TaskSchedule::create([
            'task_id' => $task->delegate_task_id,
            'assigned_date' => Carbon::now('Asia/Kolkata'),
            'status' => 'Pending',
            'frequency' => $request->frequency,
            'created_by' => $activeUser,
            'updated_by' => $activeUser,
        ]);


        // Handle file uploads
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $document) {
                $fileName = $this->storeFile($document);

                TaskMedia::create([
                    'task_id' => $task->delegate_task_id,
                    'category' => 'document',
                    'file_name' => $fileName,
                    'created_by' => $activeUser
                ]);
            }
        }

        // Handle voice notes from JSON
        if ($request->hasFile('voice_notes')) {
            foreach ($request->file('voice_notes') as $voiceNote) {
                if ($voiceNote->isValid()) {
                    $fileName = 'voice_note_' . time() . '_' . uniqid() . '.wav';
                    $voiceNote->move(public_path('assets/uploads'), $fileName);
                    // ... create record ...
                    TaskMedia::create([
                        'task_id' => $task->delegate_task_id,
                        'category' => 'voice_note',
                        'file_name' => $fileName,
                        'created_by' => $activeUser
                    ]);
                }
            }
        }
        // Handle links
        $links = json_decode($request->links_json, true) ?? [];
        foreach ($links as $link) {
            if (!empty($link)) {
                TaskMedia::create([
                    'task_id' => $task->delegate_task_id,
                    'category' => 'link',
                    'file_name' => $link,
                    'created_by' => $activeUser
                ]);
            }
        }

        // Handle Tasks
        $task_list = json_decode($request->tasks_json, true) ?? [];
        foreach ($task_list as $taskItem) {
            if (!empty($taskItem)) {
                TaskList::create([
                    'task_id' => $task->delegate_task_id,
                    'tasks' => $taskItem,
                    'assign_to' => $request->assign_to,
                    'assign_by' => $activeUser,
                    'updated_by' => $activeUser
                ]);
            }
        }

        try {
            $telegramConfig = DB::table('telegram_rn')->where('name', 'task')->first();
            $botId = $telegramConfig->botId ?? null;

            if (!$botId) {
                Log::error('Telegram Bot ID not found for task notification.');
                return;
            }

            $assign_to_parts = explode('*', $request->assign_to);
            $assign_to_employee_code = trim($assign_to_parts[0]);

            $assignChatId = DB::table('users')->where('employee_code', $assign_to_employee_code)->value('telegram_chat_id');

            if ($assignChatId) {
                $message = "📢 <b>New Task Delegated</b>\n\n";
                $message .= "📌 <b>Title:</b> " . e($request->delegate_task_title) . "\n";
                $message .= "⚠️ <b>Priority:</b> " . e($request->priority) . "\n";
                $message .= "⏰ <b>Due Date:</b> " . ($request->due_date ?: 'Not Set') . "\n";
                $message .= "👤 <b>Delegated To:</b> " . e($request->assign_to) . "\n";
                $message .= "🧑‍💼 <b>Delegated By:</b> " . e($user->employee_name) . "\n";

                $inlineKeyboard = [
                    'inline_keyboard' => [
                        [
                            ['text' => '📂 Open Task!', 'url' => url('task_detail.php')]
                        ]
                    ]
                ];
                $response = Http::post("https://api.telegram.org/bot{$botId}/sendMessage", [
                    'chat_id' => $assignChatId,
                    'text' => $message,
                    'parse_mode' => 'HTML',
                    'reply_markup' => json_encode($inlineKeyboard)
                ]);

                $responseData = $response->json();
                Log::info('Telegram Response:', $responseData);

                if (!empty($responseData['ok']) && !empty($responseData['result'])) {
                    TelegramWebhookMessage::create([
                        'chat_id' => $assignChatId,
                        'message_id' => $responseData['result']['message_id'],
                        'name' => Auth::guard('web')->check() ? Auth::guard('web')->user()->employee_name : null,
                        'username' => 'TelegramBot',
                        'message_text' => $message,
                        'message_array_data' => json_encode($responseData),
                        'messageDate' => Carbon::now('Asia/Kolkata'),
                        'created_at' => Carbon::now('Asia/Kolkata'),
                        'updated_at' => Carbon::now('Asia/Kolkata')
                    ]);

                    TaskLog::create([
                        'task_id' => $request->delegate_task_id,
                        'log_description' => 'Task Delegated Notification Send on Telegram',
                        'added_by' => $activeUser
                    ]);

                    TelegramWhatsappLog::create([
                        'task_id' => $request->delegate_task_id,
                        'log_description' => 'Task Delegated Notification Send on Telegram',
                        'notification_on' => 'Telegram'
                    ]);
                } else {
                    Log::warning("Telegram message not sent. Reason: " . ($responseData['description'] ?? 'Unknown'));

                    TaskLog::create([
                        'task_id' => $request->delegate_task_id,
                        'log_description' => 'Task Delegated Notification Not Send on Telegram Chat ID Not Register',
                        'added_by' => $activeUser
                    ]);
                }
            } else {
                Log::warning("No Telegram chat ID found for employee code: {$assign_to_employee_code}");
            }

            $assign_to_employee_code = trim($assign_to_parts[0]);
            $assignToMobileNoPersonal = DB::table('users')->where('employee_code', $assign_to_employee_code)->value('mobile_no_personal');

            if ($assignToMobileNoPersonal) {
                $waMessage = "📢 *New Task Delegated*\n\n";
                $waMessage .= "📌 Title: " . e($request->delegate_task_title) . "\n";
                $waMessage .= "⚠️ Priority: " . e($request->priority) . "\n";
                $waMessage .= "⏰ Due Date: " . ($request->due_date ?: 'Not Set') . "\n";
                $waMessage .= "👤 Delegated To: " . e($request->assign_to) . "\n";
                $waMessage .= "🧑‍💼 Delegated By: " . e($user->employee_name) . "\n";

                $response = $this->whatsapp->sendWaMessage(
                    '169617539573196',
                    'EAAEY6g6mDSUBO4a9RKn5hBaFTL4YbYxaA3LlDlMKHNLWVqnTQpHXWw6V8Ta6P7SRg7dJeaaDnsXyRKNHKZCdXcbZCmP4KgAZAwDCAdxUnBgwXx0nW79EyPpUhkuBLN8BWkVnrmsrmZB7yqQHtxONqmzCASGTW6AJTaeZAoZAiC09etCHMnL4ZBV7GnnWqEwdZBzo',
                    $assignToMobileNoPersonal,
                    'text',
                    ['body' => $waMessage],
                    '',
                    '',
                    ''
                );
                TaskLog::create([
                    'task_id' => $request->delegate_task_id,
                    'log_description' => 'Task Delegated Notification Send on Whatsapp',
                    'added_by' => $activeUser
                ]);

                TelegramWhatsappLog::create([
                    'task_id' => $request->delegate_task_id,
                    'log_description' => 'Task Delegated Notification Send on Telegram',
                    'notification_on' => 'Whatsapp'
                ]);
            } else {
                TaskLog::create([
                    'task_id' => $request->delegate_task_id,
                    'log_description' => 'delegate_task_id Delegated Notification Not Send on Whatsapp Mobile No not Register',
                    'added_by' => $activeUser
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Telegram Notification Failed: ' . $e->getMessage());
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Task delegated successfully!',
            'task_id' => $task->delegate_task_id
        ]);
    }

    public function getTaskById($id)
    {
        $task = Task::where('task_id', $id)->first();

        if (!$task) {
            return response()->json(['error' => 'Task not found.'], 404);
        }

        return response()->json([
            'id' => $task->task_id,
            'title' => $task->title,
        ]);
    }

    public function taskDetails2($task_id)
    {
        $activeUser = Auth::user();
        $usercode = $activeUser->employee_code . '*' . $activeUser->employee_name;

        // Determine if it's a delegated task
        $isDelegated = str_starts_with($task_id, 'DELTASK');

        // Fetch task and delegated tasks
        $delegatedTasks = $this->getDelegatedTasks($task_id, $isDelegated);
        $task = $this->getMainTask($task_id, $isDelegated, $delegatedTasks);

        if ($isDelegated && $this->isHiddenFromUser($delegatedTasks, $usercode)) {
            return false;
        }

        $delegatedTaskIds = $delegatedTasks->pluck('delegate_task_id')->toArray();
        $allTaskIds = array_unique(array_merge([$task->task_id], $delegatedTaskIds));

        // Load related items
        $taskItems = TaskList::whereIn('task_id', $allTaskIds)->get();
        $taskComments = TaskComment::whereIn('task_id', $allTaskIds)->get();
        $taskMedias = TaskMedia::whereIn('task_id', $allTaskIds)->get();
        $activities = TaskLog::whereIn('task_id', $allTaskIds)->orderByDesc('id')->get();

        $stats = $this->generateStats(
            $task,
            $delegatedTasks,
            $taskItems,
            $taskComments,
            $taskMedias
        );

        $team = $this->getTaskTeam($task, $delegatedTasks);
        $teamCount = count($team);
        $userWiseStats = $this->generateUserWiseStats($stats['individualStats']);
        $activities = TaskLog::whereIn('task_id', $allTaskIds)->orderByDesc('id')->get();
        $totalActivity = $activities->count(); // Calculate total activities
        $lastActivity = $activities->first();

        return view('tasks.taskDetails', compact(
            'task',
            'delegatedTasks',
            'taskItems',
            'taskMedias',
            'taskComments',
            'stats',
            'team',
            'teamCount',
            'activities',
            'totalActivity', // Add this
            'lastActivity',  // Add this
            'userWiseStats',
            'activeUser'
        ));
    }

    private function getDelegatedTasks($task_id, $isDelegated)
    {
        return $isDelegated
            ? DelegatedTask::where('delegate_task_id', $task_id)->get()
            : DelegatedTask::where('task_id', $task_id)->get();
    }

    private function getMainTask($task_id, $isDelegated, $delegatedTasks)
    {
        if ($isDelegated) {
            $realTaskId = optional($delegatedTasks->first())->task_id;
            return Task::where('task_id', $realTaskId)->firstOrFail();
        }
        return Task::where('task_id', $task_id)->firstOrFail();
    }

    private function isHiddenFromUser($delegatedTasks, $usercode)
    {
        foreach ($delegatedTasks as $delegatedTask) {
            $visibleTo = json_decode($delegatedTask->not_visible_to, true);
            if (is_array($visibleTo) && in_array($usercode, $visibleTo)) {
                return true;
            }
        }
        return false;
    }

    private function generateStats($task, $delegatedTasks, $taskItems, $taskComments, $taskMedias)
    {
        $delegatedTaskIds = $delegatedTasks->pluck('delegate_task_id')->toArray();
        $allTaskIds = array_unique(array_merge([$task->task_id], $delegatedTaskIds));

        $individualStats = [];

        foreach ($allTaskIds as $id) {
            $items = $taskItems->where('task_id', $id);
            $comments = $taskComments->where('task_id', $id);
            $medias = $taskMedias->where('task_id', $id);

            $title = $this->getTaskTitle($id, $task, $delegatedTasks);
            $assignTo = $this->getAssignTo($id, $task, $delegatedTasks);
            $teamMembers = $this->getTeamMembers($assignTo);

            $individualStats[] = [
                'task_id' => $id,
                'title' => $title,
                'priority' => $this->getTaskProperty($id, $task, $delegatedTasks, 'priority'),
                'status' => $this->getTaskProperty($id, $task, $delegatedTasks, 'status'),
                'assign_at' => $this->getTaskProperty($id, $task, $delegatedTasks, 'created_at'),
                'assign_to' => $assignTo,
                'total' => $items->count(),
                'completed' => $items->where('status', 'Completed')->count(),
                'progress' => $items->count() ? round(($items->where('status', 'Completed')->count() / $items->count()) * 100, 2) : 0,
                'totalComments' => $comments->count(),
                'totalMedias' => $medias->count(),
                'teamMembers' => $teamMembers,
                'task_list_items' => $items,
                'comment_list_items' => $comments,
            ];
        }

        $total = $taskItems->count();
        $completed = $taskItems->where('status', 'Completed')->count();
        $progressPercentage = $total > 0 ? round(($completed / $total) * 100, 2) : 0;

        $inProcess = $taskItems->where('status', 'In Progress')->count();

        return compact('total', 'completed', 'progressPercentage', 'individualStats', 'inProcess');
    }

    private function getTaskTitle($id, $task, $delegatedTasks)
    {
        if ($id == $task->task_id)
            return $task->title;

        return optional($delegatedTasks->firstWhere('delegate_task_id', $id))->title ?? 'Untitled';
    }

    private function getAssignTo($id, $task, $delegatedTasks)
    {
        $entry = $id == $task->task_id
            ? $task->assign_to
            : optional($delegatedTasks->firstWhere('delegate_task_id', $id))->assign_to;

        return $entry ? [$entry] : [];
    }

    private function getTaskProperty($id, $task, $delegatedTasks, $property)
    {
        return $id == $task->task_id
            ? $task->$property
            : optional($delegatedTasks->firstWhere('delegate_task_id', $id))->$property;
    }

    private function getTeamMembers($assignToRaw)
    {
        $employeeCodes = collect($assignToRaw)->map(function ($entry) {
            return explode('*', $entry)[0] ?? null;
        })->filter()->unique();

        return User::whereIn('employee_code', $employeeCodes)->get([
            'employee_code',
            'employee_name',
            'profile_picture'
        ]);
    }

    private function getTaskTeam($task, $delegatedTasks)
    {
        $mainCode = explode('*', $task->assign_to)[0] ?? null;

        $delegatedCodes = $delegatedTasks->pluck('assign_to')->map(function ($a) {
            return explode('*', $a)[0] ?? null;
        });

        $empIds = collect($delegatedCodes)->merge([$mainCode])->filter()->unique();

        return User::whereIn('employee_code', $empIds)->get(['employee_code', 'employee_name', 'profile_picture']);
    }

    private function generateUserWiseStats($individualStats)
    {
        $stats = [];

        foreach ($individualStats as $taskData) {
            foreach ($taskData['assign_to'] as $assignTo) {
                [$empCode, $empName] = explode('*', $assignTo);

                if (!isset($stats[$empCode])) {
                    $stats[$empCode] = [
                        'employee_code' => $empCode,
                        'employee_name' => $empName ?? '',
                        'tasks' => [],
                        'total_tasks' => 0,
                        'completed_tasks' => 0,
                        'progress' => 0,
                    ];
                }

                $stats[$empCode]['tasks'][] = [
                    'task_id' => $taskData['task_id'],
                    'title' => $taskData['title'],
                    'status' => $taskData['status'],
                    'progress' => $taskData['progress'],
                    'priority' => $taskData['priority'],
                    'assign_at' => $taskData['assign_at'],
                    'total' => $taskData['total'],
                    'completed' => $taskData['completed'],
                    'task_list_items' => $taskData['task_list_items'],
                ];

                $stats[$empCode]['total_tasks'] += $taskData['total'];
                $stats[$empCode]['completed_tasks'] += $taskData['completed'];
            }
        }

        foreach ($stats as &$userStat) {
            $userStat['progress'] = $userStat['total_tasks'] > 0
                ? round(($userStat['completed_tasks'] / $userStat['total_tasks']) * 100, 2)
                : 0;
        }

        return $stats;
    }

    public function addComment(Request $request)
    {
        $user = Auth::user();
        $activeUser = $user->employee_code . "*" . $user->employee_name;

        // Validate the main task data
        $validator = Validator::make($request->all(), [
            'task_id' => 'required|string|max:255',
            'comment' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'messages' => $validator->errors()
            ], 422);
        }

        $comment = TaskComment::create([
            'task_id' => $request->task_id,
            'comment' => $request->comment,
            'added_by' => $activeUser
        ]);

        if ($comment) {
            return response()->json([
                'status' => 'success',
                'message' => 'Comment added successfully!',
            ]);
        }
    }

    public function dashboard()
    {
        $taskLists = TaskList::latest()->get();
        $totalTasks = TaskList::count();
        $completedTasks = TaskList::where('status', 'Completed')->count();
        $inProcess = TaskList::where('status', 'In Progress')->count();
        $pending = TaskList::where('status', 'Pending')->count();

        return view('dashboard', [
            'taskLists' => $taskLists,
            'totalTasks' => $totalTasks,
            'completedTasks' => $completedTasks,
            'inProcess' => $inProcess,
            'pending' => $pending,
        ]);
    }
    public function taskDetails($task_id)
    {
        $activeUser = Auth::user();
        $usercode = $activeUser->employee_code . '*' . $activeUser->employee_name;
        $task = [];
        $clubbedInfo = [];
        $groupedByTask = [];
        $groupedByUser = [];

        $isDelegated = str_starts_with($task_id, 'DELTASK');

        if ($isDelegated) {
            // Handle delegated task case (single task)
            $taskItem = DelegatedTask::where('delegate_task_id', $task_id)->firstOrFail();
            $task['task_info'] = $taskItem->toArray();
            $allTaskIds = $task_id;

            // Extract member codes with relationship flags
            $assignToCode = $taskItem->assign_to ? explode('*', $taskItem->assign_to)[0] : null;
            $assignByCode = $taskItem->assign_by ? explode('*', $taskItem->assign_by)[0] : null;
            $allMemberCodes = array_filter([$assignToCode, $assignByCode]);

            $teamMembers = User::whereIn('employee_code', $allMemberCodes)
                ->get(['employee_code', 'employee_name', 'profile_picture'])
                ->map(function ($member) use ($assignToCode, $assignByCode) {
                    return [
                        'employee_code' => $member->employee_code,
                        'employee_name' => $member->employee_name,
                        'profile_picture' => $member->profile_picture,
                        'is_assigned_to' => $member->employee_code === $assignToCode,
                        'is_assigned_by' => $member->employee_code === $assignByCode,
                        'relationship' => $member->employee_code === $assignToCode ? 'assigned_to' : ($member->employee_code === $assignByCode ? 'assigned_by' : null)
                    ];
                });

            $groupedByTask[$task_id] = [
                'each_task_info' => $taskItem->toArray(),
                'team_members' => $teamMembers->toArray(),
                'assign_to_code' => $assignToCode,
                'assign_by_code' => $assignByCode
            ];
        } else {
            // Handle regular task case
            $mainTask = Task::where('task_id', $task_id)->firstOrFail();
            $delegatedTasks = DelegatedTask::where('task_id', $task_id)->get();

            $delegatedTaskIds = $delegatedTasks->pluck('delegate_task_id')->toArray();
            $task['task_info'] = $mainTask->toArray();
            $allTaskIds = array_unique(array_merge([$mainTask->task_id], $delegatedTaskIds));

            // Process main task with relationship flags
            $mainAssignToCode = $mainTask->assign_to ? explode('*', $mainTask->assign_to)[0] : null;
            $mainAssignByCode = $mainTask->assign_by ? explode('*', $mainTask->assign_by)[0] : null;
            $mainMemberCodes = array_filter([$mainAssignToCode, $mainAssignByCode]);

            $mainTeamMembers = User::whereIn('employee_code', $mainMemberCodes)
                ->get(['employee_code', 'employee_name', 'profile_picture'])
                ->map(function ($member) use ($mainAssignToCode, $mainAssignByCode) {
                    return [
                        'employee_code' => $member->employee_code,
                        'employee_name' => $member->employee_name,
                        'profile_picture' => $member->profile_picture,
                        'is_assigned_to' => $member->employee_code === $mainAssignToCode,
                        'is_assigned_by' => $member->employee_code === $mainAssignByCode,
                        'relationship' => $member->employee_code === $mainAssignToCode ? 'assigned_to' : ($member->employee_code === $mainAssignByCode ? 'assigned_by' : null)
                    ];
                });

            $groupedByTask[$mainTask->task_id] = [
                'each_task_info' => $mainTask->toArray(),
                'team_members' => $mainTeamMembers->toArray(),
                'assign_to_code' => $mainAssignToCode,
                'assign_by_code' => $mainAssignByCode
            ];

            // Process each delegated task with relationship flags
            foreach ($delegatedTasks as $delegatedTask) {
                $delegatedAssignToCode = $delegatedTask->assign_to ? explode('*', $delegatedTask->assign_to)[0] : null;
                $delegatedAssignByCode = $delegatedTask->assign_by ? explode('*', $delegatedTask->assign_by)[0] : null;
                $delegatedMemberCodes = array_filter([$delegatedAssignToCode, $delegatedAssignByCode]);

                $delegatedTeamMembers = User::whereIn('employee_code', $delegatedMemberCodes)
                    ->get(['employee_code', 'employee_name', 'profile_picture'])
                    ->map(function ($member) use ($delegatedAssignToCode, $delegatedAssignByCode) {
                        return [
                            'employee_code' => $member->employee_code,
                            'employee_name' => $member->employee_name,
                            'profile_picture' => $member->profile_picture,
                            'is_assigned_to' => $member->employee_code === $delegatedAssignToCode,
                            'is_assigned_by' => $member->employee_code === $delegatedAssignByCode,
                            'relationship' => $member->employee_code === $delegatedAssignToCode ? 'assigned_to' : ($member->employee_code === $delegatedAssignByCode ? 'assigned_by' : null)
                        ];
                    });

                $groupedByTask[$delegatedTask->delegate_task_id] = [
                    'each_task_info' => $delegatedTask->toArray(),
                    'team_members' => $delegatedTeamMembers->toArray(),
                    'assign_to_code' => $delegatedAssignToCode,
                    'assign_by_code' => $delegatedAssignByCode
                ];
            }
        }

        // Get all unique member codes for clubbedInfo
        $allMemberCodes = [];
        foreach ($groupedByTask as $taskData) {
            if (isset($taskData['each_task_info']['assign_to'])) {
                $allMemberCodes[] = explode('*', $taskData['each_task_info']['assign_to'])[0];
            }
            if (isset($taskData['each_task_info']['assign_by'])) {
                $allMemberCodes[] = explode('*', $taskData['each_task_info']['assign_by'])[0];
            }
        }
        $allMemberCodes = array_values(array_unique(array_filter($allMemberCodes)));
        $allTaskIds = (array) $allTaskIds;

        // clubbedInfo
        $teamMembers = User::whereIn('employee_code', $allMemberCodes)
            ->get(['employee_code', 'employee_name', 'profile_picture']);

        $clubbedInfo['teamMembers'] = $teamMembers->toArray();
        $clubbedInfo['teamMembersCount'] = $teamMembers->count();

        $taskLists = TaskList::whereIn('task_id', $allTaskIds)->get();
        $taskLogs = TaskLog::whereIn('task_id', $allTaskIds)->get();
        $totalActivity = $taskLogs->count();
        $lastActivity = $taskLogs->first()?->created_at;

        $clubbedInfo['taskListCount'] = $taskLists->count();
        $clubbedInfo['taskListPendingCount'] = $taskLists->where('status', 'Pending')->count();
        $clubbedInfo['taskInProcessListCount'] = $taskLists->where('status', 'In Process')->count();
        $clubbedInfo['taskInCompletedListCount'] = $taskLists->where('status', 'Completed')->count();
        $clubbedInfo['taskprogressPercentage'] = $clubbedInfo['taskListCount'] > 0 ? round(($clubbedInfo['taskInCompletedListCount'] / $clubbedInfo['taskListCount']) * 100, 2) : 0;
        $clubbedInfo['totalActivity'] = $totalActivity;
        $clubbedInfo['lastActivity'] = $lastActivity;
        $clubbedInfo['activities'] = $taskLogs;

        $task['clubbedInfo'] = $clubbedInfo;

        // groupedByTask
        $taskDocuments = TaskMedia::whereIn('task_id', $allTaskIds)
            ->where('category', 'document')
            ->get()
            ->groupBy(['task_id']);

        $taskVoiceNotes = TaskMedia::whereIn('task_id', $allTaskIds)
            ->where('category', 'voice_note')
            ->get()
            ->groupBy(['task_id']);

        $taskLinks = TaskMedia::whereIn('task_id', $allTaskIds)
            ->where('category', 'link')
            ->get()
            ->groupBy(['task_id']);

        $taskLists = TaskList::whereIn('task_id', $allTaskIds)
            ->get()
            ->groupBy('task_id');

        $taskComments = TaskComment::whereIn('task_id', $allTaskIds)
            ->get()
            ->groupBy('task_id');

        // Build the final grouped structure
        // Build the final grouped structure
        foreach ($allTaskIds as $taskId) {
            $documentsForTask = $taskDocuments->get($taskId, []);
            $voiceNotesForTask = $taskVoiceNotes->get($taskId, []);
            $linksForTask = $taskLinks->get($taskId, []);
            $listsForTask = $taskLists->get($taskId, []);
            $commentsForTask = $taskComments->get($taskId, []);
            $attachmentsCount = count($documentsForTask) + count($voiceNotesForTask) + count($linksForTask);

            $existingData = $groupedByTask[$taskId] ?? [];

            // Calculate task status counts for THIS SPECIFIC TASK
            $completed = collect($listsForTask)->where('status', 'Completed')->count();
            $pending = collect($listsForTask)->where('status', 'Pending')->count(); // Fixed typo 'Pening'
            $totalLists = count($listsForTask);

            $groupedByTask[$taskId] = array_merge($existingData, [
                'documents' => $documentsForTask,
                'documentsCount' => count($documentsForTask), // Fixed: was returning array instead of count
                'voice_notes' => $voiceNotesForTask,
                'voiceNotesCount' => count($voiceNotesForTask), // Added missing count
                'links' => $linksForTask,
                'linksCount' => count($linksForTask), // Added missing count
                'lists' => $listsForTask,
                'listsCount' => $totalLists,
                'comments' => $commentsForTask,
                'commentsCount' => count($commentsForTask),
                'attachmentsCount' => $attachmentsCount,
                'completed_task' => $completed,
                'pending_task' => $pending,
                'task_progress' => $totalLists > 0 ? round(($completed / $totalLists) * 100) : 0, // Calculate progress percentage
                'total_task' => $totalLists // Added total task count
            ]);
        }
        $task['groupedByTask'] = $groupedByTask;

        // groupedByUser
        foreach ($groupedByTask as $taskId => $taskData) {
            $assignToCode = $taskData['assign_to_code'] ?? null;
            $assignByCode = $taskData['assign_by_code'] ?? null;
            $currentUserIsAssignee = $assignToCode === $activeUser->employee_code;
            $currentUserIsAssigner = $assignByCode === $activeUser->employee_code;

            foreach (array_filter([$assignToCode, $assignByCode]) as $userCode) {
                if (!isset($groupedByUser[$userCode])) {
                    $user = User::where('employee_code', $userCode)
                        ->first(['employee_code', 'employee_name', 'profile_picture']);

                    if ($user) {
                        $groupedByUser[$userCode] = [
                            'employee_code' => $user->employee_code,
                            'employee_name' => $user->employee_name,
                            'profile_picture' => $user->profile_picture,
                            'tasks' => [],
                            'total_task' => 0,
                            'completed_task' => 0,
                            'pending_task' => 0,
                            'progress' => 0,

                        ];
                    }
                }

                if (isset($groupedByUser[$userCode])) {
                    $lists = $taskData['lists'] ?? [];
                    $completed = collect($lists)->where('status', 'Completed')->count();
                    $pending = collect($lists)->where('status', 'Pending')->count();

                    $groupedByUser[$userCode]['tasks'][] = [
                        'task_id' => $taskId,
                        'task_title' => $taskData['each_task_info']['title'] ?? 'No Title',
                        'total_task' => count($lists),
                        'completed_task' => $completed,
                        'pending_task' => $pending,
                        'assign_by' => $taskData['each_task_info']['assign_by'] ?? 'No Title',
                    ];

                    $groupedByUser[$userCode]['total_task'] += count($lists);
                    $groupedByUser[$userCode]['completed_task'] += $completed;
                    $groupedByUser[$userCode]['pending_task'] += $pending;
                    $groupedByUser[$userCode]['progress'] > 0 ? round(($groupedByUser[$userCode]['completed_task'] / $groupedByUser[$userCode]['total_task']) * 100, 2) : 0;
                }
            }
        }
        // Add the groupedByUser to the final task array
        $task['groupedByUser'] = $groupedByUser;
        // dd($task);

        return view('tasks.taskDetails', compact('task'));
    }

    public function deleteTask(Request $request)
    {
        $taskId = $request->task_id;

        if (!$taskId) {
            return response()->json([
                'status' => 'error',
                'message' => 'No task ID provided.'
            ], 400);
        }

        // If the task is a delegated task
        if (Str::startsWith($taskId, 'DELTASK-')) {
            $task = DelegatedTask::where('delegate_task_id', $taskId)->first();

            if (!$task) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Delegated task not found.'
                ], 404);
            }

            // Delete all related records by delegate_task_id
            TaskSchedule::where('task_id', $taskId)->delete();
            TaskMedia::where('task_id', $taskId)->delete();
            TaskLog::where('task_id', $taskId)->delete();
            TaskList::where('task_id', $taskId)->delete();
            TaskComment::where('task_id', $taskId)->delete();

            $task->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Delegated task and all its associated data were deleted successfully.'
            ]);
        }
        // Else, it's a main task
        else {
            $task = Task::where('task_id', $taskId)->first();

            if (!$task) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Task not found.'
                ], 404);
            }

            // Delete all related records by task_id
            DelegatedTask::where('task_id', $taskId)->delete();
            TaskSchedule::where('task_id', $taskId)->delete();
            TaskMedia::where('task_id', $taskId)->delete();
            TaskLog::where('task_id', $taskId)->delete();
            TaskList::where('task_id', $taskId)->delete();
            TaskComment::where('task_id', $taskId)->delete();

            $task->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Task and all its associated data were deleted successfully.'
            ]);
        }
    }

}
