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
        } catch (\Exception $e) {
            Log::error('Telegram Notification Failed: ' . $e->getMessage());
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

    // public function pendingTask()
    // {
    //     $user = Auth::user();
    //     $assign_to = $user->employee_code . '*' . $user->employee_name;
    //     $tasks = Task::where('assign_to', $assign_to)
    //         ->orderBy('due_date', 'asc')
    //         ->get();
    //     return view('tasks.pendingTask',compact('tasks'));

    // }

    public function pendingTask()
    {
        $user = Auth::user();
        $assign_to = $user->employee_code . '*' . $user->employee_name;

        // Get main tasks assigned to the user
        $tasks = Task::where('assign_to', $assign_to)
            ->where('status', '!=', 'Completed') // Only pending tasks
            ->get();

        // Get delegated tasks assigned to the user
        $delegatedTasks = DelegatedTask::where('assign_to', $assign_to)
            ->where('status', '!=', 'Completed') // Only pending tasks
            ->get();

        // Combine both collections
        $allTasks = $tasks->merge($delegatedTasks);

        // Group tasks by priority
        $groupedTasks = [
            'High' => $allTasks->where('priority', 'high'),
            'Medium' => $allTasks->where('priority', 'medium'),
            'Low' => $allTasks->where('priority', 'low')
        ];

        return view('tasks.pendingTask', compact('groupedTasks'));
    }

    // public function pendingTask()
    // {
    //     $user = Auth::user();
    //     $assign_to = $user->employee_code . '*' . $user->employee_name;

    //     // Get main tasks assigned to the user
    //     $tasks = Task::where('assign_to', $assign_to)
    //         ->where('status', '!=', 'Completed')
    //         ->with(['taskItems', 'taskMedias'])
    //         ->get();

    //     // Get delegated tasks assigned to the user
    //     $delegatedTasks = DelegatedTask::where('assign_to', $assign_to)
    //         ->where('status', '!=', 'Completed')
    //         ->with(['taskItems', 'taskMedias'])
    //         ->get();

    //     // Combine both collections
    //     $allTasks = $tasks->merge($delegatedTasks);

    //     // Process each task to include stats
    //     $processedTasks = $allTasks->map(function ($task) {
    //         // Calculate progress stats
    //         $total = $task->taskItems->count();
    //         $completed = $task->taskItems->where('status', 'Completed')->count();
    //         $progress = $total > 0 ? round(($completed / $total) * 100, 2) : 0;

    //         // Count comments and media
            
    //         $totalMedias = $task->taskMedias->count();

    //         // Get team members
    //         $assign_to_raw = is_array($task->assign_to) ? $task->assign_to : [$task->assign_to];
    //         $employeeCodes = collect($assign_to_raw)->map(function ($entry) {
    //             $parts = explode('*', $entry);
    //             return $parts[0] ?? null;
    //         })->filter()->toArray();

    //         $teamMembers = User::whereIn('employee_code', $employeeCodes)
    //             ->get(['employee_code', 'employee_name', 'profile_picture']);

    //         // Add calculated fields to task
    //         $task->total = $total;
    //         $task->completed = $completed;
    //         $task->progress = $progress;
    //         $task->totalMedias = $totalMedias;
    //         $task->teamMembers = $teamMembers;

    //         return $task;
    //     });

    //     // Group tasks by priority
    //     $groupedTasks = [
    //         'High' => $processedTasks->where('priority', 'high'),
    //         'Medium' => $processedTasks->where('priority', 'medium'),
    //         'Low' => $processedTasks->where('priority', 'low')
    //     ];

    //     return view('tasks.pendingTask', compact('groupedTasks'));
    // }

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
        } catch (\Exception $e) {
            Log::error('Telegram Notification Failed: ' . $e->getMessage());
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
        // $task_id_parts = explode('', $task_id);
        // if ($task_id_parts[0] === "DELTASK") {
        // } else {
        // }

        // Fetch the main task or throw 404 if not found
        $task = Task::where('task_id', $task_id)->firstOrFail();

        // Fetch delegated tasks and their IDs
        $delegatedTasks = DelegatedTask::where('task_id', $task_id)->get();
        $delegatedTaskIds = $delegatedTasks->pluck('delegate_task_id')->toArray();

        // Combine main and delegated task IDs
        $allTaskIds = array_unique(array_merge([$task->task_id], $delegatedTaskIds));

        // Fetch task items related to all tasks
        $taskItems = TaskList::whereIn('task_id', $allTaskIds)->get();
        $taskComments = TaskComment::whereIn('task_id', $allTaskIds)->get();
        $taskMedias = TaskMedia::whereIn('task_id', $allTaskIds)->get();

        // Total stats (combined)
        $totalTasks = $taskItems->count();
        $completedTasks = $taskItems->where('status', 'Completed')->count();
        $inProcess = $taskItems->where('status', 'In Progress')->count();
        $progressPercentage = $totalTasks > 0
            ? round(($completedTasks / $totalTasks) * 100, 2)
            : 0;

        // Individual stats for each task ID
        $individualStats = [];

        foreach ($allTaskIds as $id) {
            $items = $taskItems->where('task_id', $id);
            $total = $items->count();
            $completed = $items->where('status', 'Completed')->count();
            $progress = $total > 0 ? round(($completed / $total) * 100, 2) : 0;

            $commentItems = $taskComments->where('task_id', $id);
            $totalComments = $commentItems->count();

            foreach ($commentItems as $commentItem) {
                // Set task title
                if (!empty($commentItem->task_id)) {
                    $parts = explode('-', $commentItem->task_id);
                    if ($parts[0] === "DELTASK") {
                        $commentItem->task_title = DelegatedTask::where('delegate_task_id', $commentItem->task_id)->value('title');
                    } else {
                        $commentItem->task_title = Task::where('task_id', $commentItem->task_id)->value('title');
                    }
                } else {
                    $commentItem->task_title = 'Untitled';
                }

                // Set user profile
                if (!empty($commentItem->added_by)) {
                    $added_by_parts = explode('*', $commentItem->added_by);
                    $user = User::where('employee_code', $added_by_parts[0])->first(['employee_name', 'profile_picture']);
                    $commentItem->added_by_name = optional($user)->employee_name ?? 'Unknown';
                    $commentItem->added_by_picture = optional($user)->profile_picture ?? 'user.png';
                } else {
                    $commentItem->added_by_name = 'Unknown';
                    $commentItem->added_by_picture = 'user.png';
                }
            }


            $mediaItems = $taskMedias->where('task_id', $id);
            $totalMedias = $mediaItems->count();

            foreach ($mediaItems as $mediaItem) {
                // Check if it's a delegated task based on task_id format
                if (!empty($mediaItem->task_id)) {
                    $totalMediasParts = explode('-', $mediaItem->task_id);

                    if ($totalMediasParts[0] === "DELTASK") {
                        $taskTitle = DelegatedTask::where('delegate_task_id', $mediaItem->task_id)->value('title');
                    } else {
                        $taskTitle = Task::where('task_id', $mediaItem->task_id)->value('title');
                    }

                    // Attach the title dynamically
                    $mediaItem->task_title = $taskTitle;
                } else {
                    $mediaItem->task_title = 'Untitled';
                }
            }

            // If it's the main task
            if ($id == $task->task_id) {
                $title = $task->title;
                $priority = $task->priority;
                $status = $task->status;
                $assign_at = $task->created_at;
                $assign_to_raw = [$task->assign_to]; // Make it an array for consistency
            } else {
                $delegated = $delegatedTasks->firstWhere('delegate_task_id', $id);
                $title = optional($delegated)->title ?? 'Untitled';
                $priority = optional($delegated)->priority ?? null;
                $status = optional($delegated)->status ?? null;
                $assign_at = optional($delegated)->created_at ?? null;
                $assign_to_raw = optional($delegated)->assign_to
                    ? [optional($delegated)->assign_to]
                    : [];
            }

            // Extract employee codes
            $employeeCodes = [];
            foreach ($assign_to_raw as $entry) {
                $parts = explode('*', $entry);
                if (!empty($parts[0])) {
                    $employeeCodes[] = $parts[0];
                }
            }

            // Fetch team members' profile pictures
            $teamMembers = User::whereIn('employee_code', $employeeCodes)
                ->get(['employee_code', 'employee_name', 'profile_picture']);

            // Push structured data
            $individualStats[] = [
                'task_id' => $id,
                'title' => $title,
                'priority' => $priority,
                'status' => $status,
                'assign_at' => $assign_at,
                'assign_to' => $assign_to_raw,
                'total' => $total,
                'totalComments' => $totalComments,
                'totalMedias' => $totalMedias,
                'completed' => $completed,
                'progress' => $progress,
                'teamMembers' => $teamMembers,
                'task_list_items' => $items,
                'comment_list_items' => $commentItems,
            ];
        }
        // dd($individualStats);
        // Extract owner employee_code
        $ownerParts = explode('*', $task->assign_to);
        $ownerId = $ownerParts[0];

        // Extract delegated task assignee employee_codes
        $delegatedAssigneesRaw = $delegatedTasks->pluck('assign_to')->toArray();
        $ids = [];

        foreach ($delegatedAssigneesRaw as $rawAssignTo) {
            $parts = explode('*', $rawAssignTo);
            if (isset($parts[0])) {
                $ids[] = $parts[0];
            }
        }

        // Merge and deduplicate employee codes
        $empIds = array_unique(array_merge($ids, [$ownerId]));

        // Fetch user details
        $team = User::whereIn('employee_code', $empIds)
            ->get(['employee_code', 'employee_name', 'profile_picture']);
        $teamCount = $team->count();

        // Fetch activity logs
        $activities = TaskLog::whereIn('task_id', $allTaskIds)
            ->orderBy('id', 'DESC')
            ->get();
        $totalActivity = $activities->count();
        $lastActivity = $activities->first();

        $userWiseStats = [];

        // Combine assign_to values from main and delegated tasks
        $allAssignments = [];

        foreach ($individualStats as $taskData) {
            foreach ($taskData['assign_to'] as $assignTo) {
                [$empCode, $empName] = explode('*', $assignTo);

                // Initialize if user not yet added
                if (!isset($userWiseStats[$empCode])) {
                    $userWiseStats[$empCode] = [
                        'employee_code' => $empCode,
                        'employee_name' => $empName ?? '',
                        'tasks' => [],
                        'total_tasks' => 0,
                        'completed_tasks' => 0,
                        'progress' => 0,
                    ];
                }

                // Add task info to the user
                $userWiseStats[$empCode]['tasks'][] = [
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

                // Update counts
                $userWiseStats[$empCode]['total_tasks'] += $taskData['total'];
                $userWiseStats[$empCode]['completed_tasks'] += $taskData['completed'];
            }
        }

        // Calculate progress for each user
        foreach ($userWiseStats as &$userStat) {
            $userStat['progress'] = $userStat['total_tasks'] > 0
                ? round(($userStat['completed_tasks'] / $userStat['total_tasks']) * 100, 2)
                : 0;
        }
        $activeUser = Auth::user();

        // dd($userWiseStats);

        return view('tasks.taskDetails', compact(
            'task',
            'delegatedTasks',
            'taskItems',
            'taskMedias',
            'taskComments',
            'totalTasks',
            'completedTasks',
            'progressPercentage',
            'teamCount',
            'team',
            'totalActivity',
            'lastActivity',
            'activities',
            'userWiseStats',
            'activeUser',
            'individualStats',
            'inProcess' // ← Include individual task stats
        ));
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
}
