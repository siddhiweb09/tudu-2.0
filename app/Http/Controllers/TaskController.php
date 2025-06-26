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
                $message  = "📢 <b>New Task Assigned</b>\n\n";
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
                } else {
                    Log::warning("Telegram message not sent. Reason: " . ($responseData['description'] ?? 'Unknown'));
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
        $user = Auth::user();
        $usercode = $user->employee_code . '*' . $user->employee_name;

        // Step 1: Get all tasks where the user is assign_to or assign_from
        $allTasks = Task::where(function ($query) use ($usercode) {
            $query->where('assign_to', $usercode)
                ->orWhere('assign_from', $usercode);
        })->get();

        // Step 2: Filter out tasks that have a delegated task with current user in visible_to
        $filteredTasks = $allTasks->filter(function ($task) use ($usercode) {
            $delegatedTasks = DelegatedTask::where('task_id', $task->task_id)->get();

            foreach ($delegatedTasks as $delegatedTask) {
                $visibleTo = json_decode($delegatedTask->visible_to, true);

                if (is_array($visibleTo) && in_array($usercode, $visibleTo)) {
                    // If usercode is in visible_to, skip this task
                    return false;
                }
            }
            // Keep the task
            return true;
        });
        return view('tasks.allTasks', compact('filteredTasks'));
    }

    public function pendingTask()
    {
        $user = Auth::user();
        $usercode = $user->employee_code . '*' . $user->employee_name;

        // Step 1: Get all tasks where the user is assign_to or assign_from
        $allTasks = Task::where(function ($query) use ($usercode) {
            $query->where('assign_to', $usercode)
                ->orWhere('assign_from', $usercode);
        })->where('status', 'Pending')->get();

        // Step 2: Filter out tasks that have a delegated task with current user in visible_to
        $filteredTasks = $allTasks->filter(function ($task) use ($usercode) {
            $delegatedTasks = DelegatedTask::where('task_id', $task->task_id)->get();

            foreach ($delegatedTasks as $delegatedTask) {
                $visibleTo = json_decode($delegatedTask->visible_to, true);

                if (is_array($visibleTo) && in_array($usercode, $visibleTo)) {
                    // If usercode is in visible_to, skip this task
                    return false;
                }
            }
            // Keep the task
            return true;
        });
        return view('tasks.allTasks', compact('filteredTasks'));
    }

    public function inProcessTask()
    {
        $user = Auth::user();
        $usercode = $user->employee_code . '*' . $user->employee_name;

        // Step 1: Get all tasks where the user is assign_to or assign_from
        $allTasks = Task::where(function ($query) use ($usercode) {
            $query->where('assign_to', $usercode)
                ->orWhere('assign_from', $usercode);
        })->where('status', 'In Process')
            ->get();

        // Step 2: Filter out tasks that have a delegated task with current user in visible_to
        $filteredTasks = $allTasks->filter(function ($task) use ($usercode) {
            $delegatedTasks = DelegatedTask::where('task_id', $task->task_id)->get();

            foreach ($delegatedTasks as $delegatedTask) {
                $visibleTo = json_decode($delegatedTask->visible_to, true);

                if (is_array($visibleTo) && in_array($usercode, $visibleTo)) {
                    // If usercode is in visible_to, skip this task
                    return false;
                }
            }
            // Keep the task
            return true;
        });
        return view('tasks.allTasks', compact('filteredTasks'));
    }

    public function inReviewTask()
    {
        $user = Auth::user();
        $usercode = $user->employee_code . '*' . $user->employee_name;

        // Step 1: Get all tasks where the user is assign_to or assign_from
        $allTasks = Task::where(function ($query) use ($usercode) {
            $query->where('assign_to', $usercode)
                ->orWhere('assign_from', $usercode);
        })->where('status', 'Completed')
            ->where('final_status', 'Pending')
            ->get();

        // Step 2: Filter out tasks that have a delegated task with current user in visible_to
        $filteredTasks = $allTasks->filter(function ($task) use ($usercode) {
            $delegatedTasks = DelegatedTask::where('task_id', $task->task_id)->get();

            foreach ($delegatedTasks as $delegatedTask) {
                $visibleTo = json_decode($delegatedTask->visible_to, true);

                if (is_array($visibleTo) && in_array($usercode, $visibleTo)) {
                    // If usercode is in visible_to, skip this task
                    return false;
                }
            }
            // Keep the task
            return true;
        });
        return view('tasks.allTasks', compact('filteredTasks'));
    }

    public function overdueTask()
    {
        $user = Auth::user();
        $usercode = $user->employee_code . '*' . $user->employee_name;
        $today = Carbon::now('Asia/Kolkata');

        // Step 1: Get all tasks where the user is assign_to or assign_from
        $allTasks = Task::where(function ($query) use ($usercode) {
            $query->where('assign_to', $usercode)
                ->orWhere('assign_from', $usercode);
        })
            ->where('status', 'Pending')
            ->whereDate('due_date', '<', $today)
            ->get();


        // Step 2: Filter out tasks that have a delegated task with current user in visible_to
        $filteredTasks = $allTasks->filter(function ($task) use ($usercode) {
            $delegatedTasks = DelegatedTask::where('task_id', $task->task_id)->get();

            foreach ($delegatedTasks as $delegatedTask) {
                $visibleTo = json_decode($delegatedTask->visible_to, true);

                if (is_array($visibleTo) && in_array($usercode, $visibleTo)) {
                    // If usercode is in visible_to, skip this task
                    return false;
                }
            }
            // Keep the task
            return true;
        });
        return view('tasks.allTasks', compact('filteredTasks'));
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
            'visible_to' => $request->visible_json,
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
                $message  = "📢 <b>New Task Delegated</b>\n\n";
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
                } else {
                    Log::warning("Telegram message not sent. Reason: " . ($responseData['description'] ?? 'Unknown'));
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

    public function taskDetails($task_id)
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
            $visibleTo = json_decode($delegatedTask->visible_to, true);
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
        if ($id == $task->task_id) return $task->title;

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

    // public function dashboard()
    // {
    //     $taskLists = TaskList::latest()->get();
    //     $totalTasks = TaskList::count();
    //     $completedTasks = TaskList::where('status', 'Completed')->count();
    //     $inProcess = TaskList::where('status', 'In Progress')->count();
    //     $pending = TaskList::where('status', 'Pending')->count();

    //     return view('dashboard', [
    //         'taskLists' => $taskLists,
    //         'totalTasks' => $totalTasks,
    //         'completedTasks' => $completedTasks,
    //         'inProcess' => $inProcess,
    //         'pending' => $pending,
    //     ]);
    // }
}
