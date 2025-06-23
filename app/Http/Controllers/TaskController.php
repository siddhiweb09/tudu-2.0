<?php
// app/Http/Controllers/TaskController.php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Models\Task;
use App\Models\TaskList;
use App\Models\TaskMedia;
use App\Models\TaskLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
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

    public function getTaskById($id)
    {
        $task = Task::where('task_id', $id)->first();

        if (!$task) {
            return response()->json(['error' => 'Task not found.'], 404);
        }

        return response()->json([
            'id' => $task->id,
            'title' => $task->title,
        ]);
    }

    public function taskDetails($task_id)
    {
        // To find by a specific column (e.g., task_id when it's not the primary key):
        $task = Task::where('task_id', $task_id)->firstOrFail();

        $taskItems = TaskList::where('task_id', $task_id)->get();

        $totalTasks = $taskItems->count();
        $completedTasks = $taskItems->where('status', 'Completed')->count(); // adjust 'completed' as needed

        $progressPercentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 2) : 0;

        $delegatedTask = DelegatedTask::where('task_id', $task_id)->firstOrFail();

        $owner = $task->assign_to;

        return view('tasks.taskDetails', compact('task', 'totalTasks', 'completedTasks', 'progressPercentage'));
    }
}
