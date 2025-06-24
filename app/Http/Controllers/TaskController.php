<?php
// app/Http/Controllers/TaskController.php
namespace App\Http\Controllers;

use App\Models\DelegatedTask;
use Illuminate\Support\Facades\Validator;
use App\Models\Task;
use App\Models\TaskList;
use App\Models\TaskMedia;
use App\Models\TaskLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();
        $activeUser = $user->employee_code . "*" . $user->employee_name;

        // Validate the main task data
        $validator = Validator::make($request->all(), [
            'task_title' => 'required|string|max:255',
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
            'task_title' => 'required|string|max:255',
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
            'task_id' =>$request->task_id,
            'title' => $request->task_title,
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
        // Fetch the main task or throw 404 if not found
        $task = Task::where('task_id', $task_id)->firstOrFail();

        // Fetch task items related to this task
        $taskItems = TaskList::where('task_id', $task_id)->get();

        // Calculate total and completed tasks
        $totalTasks = $taskItems->count();
        $completedTasks = $taskItems->where('status', 'Completed')->count();
        $progressPercentage = $totalTasks > 0
            ? round(($completedTasks / $totalTasks) * 100, 2)
            : 0;

        // Extract owner employee_code
        $ownerParts = explode('*', $task->assign_to);
        $ownerId = $ownerParts[0];

        // Extract delegated task assignee employee_codes
        $delegatedAssigneesRaw = DelegatedTask::where('task_id', $task_id)
            ->pluck('assign_to')
            ->toArray();

        $ids = [];
        foreach ($delegatedAssigneesRaw as $rawAssignTo) {
            $parts = explode('*', $rawAssignTo);
            if (isset($parts[0])) {
                $ids[] = $parts[0];
            }
        }

        // Merge and deduplicate employee codes
        $empIds = array_unique(array_merge($ids, [$ownerId]));

        // Fetch users' details
        $team = User::whereIn('employee_code', $empIds)
            ->get(['employee_code', 'employee_name', 'profile_picture']);

        $teamCount = $team->count();

        $activity = TaskLog::where('task_id', $task_id)
            ->orderBy('id', 'DESC')
            ->get();

        $totalActivity = $activity->count();
        $lastActivity = $activity->first(); // first() because it's already ordered DESC

        // Pass all required data to the view
        return view('tasks.taskDetails', compact(
            'task',
            'taskItems',
            'totalTasks',
            'completedTasks',
            'progressPercentage',
            'teamCount',
            'team',
            'totalActivity',
            'lastActivity',
            'activity'
        ));
    }
}
