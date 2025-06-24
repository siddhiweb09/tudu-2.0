<?php

namespace App\Http\Controllers;

use App\Models\PersonalTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TaskMedia;
use App\Models\TaskLog;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;



class PersonalTaskController extends Controller
{
    // List view (default)
    public function index(Request $request)
    {
        $user = Auth::user();
        $tasks = PersonalTask::where('assign_by', $user->id)
            ->orderBy('due_date', 'asc')
            ->get();

        return view('personal-tasks.index', compact('tasks'));
    }

    // Kanban view
    public function kanban(Request $request)
    {
        $user = Auth::user();
        $tasks = PersonalTask::where('assign_by', $user->id)
            ->orderBy('due_date', 'asc')
            ->get();

        return view('personal-tasks.kanban', compact('tasks'));
    }

    // Matrix view
    public function matrix(Request $request)
    {
        $user = Auth::user();
        $tasks = PersonalTask::where('assign_by', $user->id)
            ->where('status', '!=', 'completed')
            ->orderBy('due_date', 'asc')
            ->get();


        return view('personal-tasks.matrix', compact('tasks'));
    }

    // Calendar view
    public function calendar(Request $request)
    {
        $user = Auth::user();
        $month = $request->query('month', date('n'));
        $year = $request->query('year', date('Y'));

        $tasks = PersonalTask::where('assign_by', $user->id)
            ->where(function ($query) use ($month, $year) {
                $query->whereMonth('due_date', $month)
                    ->whereYear('due_date', $year);
            })
            ->orderBy('due_date', 'asc')
            ->get();



        return view('personal-tasks.calendar', compact('tasks', 'month', 'year'));
    }

    public function show(PersonalTask $task)
    {
        Log::info('Showing task:', ['task_id' => $task->task_id, 'id' => $task->id]);
        return response()->json($task);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $activeUser = $user->employee_code . "*" . $user->employee_name;
        // Decode JSON inputs
        $tasks = json_decode($request->tasks_json, true) ?? [];
        $links = json_decode($request->links_json, true) ?? [];
        $reminders = json_decode($request->reminders_json, true) ?? [];
        $frequencyDuration = json_decode($request->frequency_duration_json, true) ?? [];
        // Validate the main task data
        $validator = Validator::make($request->all(), [
            'task_title' => 'required|string|max:255',
            'task_description' => 'required|string',
            'due_date' => 'nullable|string',
            'priority' => 'required|in:high,medium,low',
            'documents' => 'nullable|array',
            'documents.*' => 'file|max:10240', // 10MB max
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
        $task = PersonalTask::create([
            'title' => $request->task_title,
            'description' => $request->task_description,
            'task_list' => $request->tasks_json,
            'due_date' => $request->due_date,
            'priority' => $request->priority,
            'is_recurring' => $request->has('recurring') ? true : false,
            'frequency' => $request->frequency,
            'frequency_duration' => $request->frequency_duration_json,
            'reminders' => $request->reminders_json,
            'links' => $request->links_json,
            'status' => 'Pending',
            'assign_by' =>  $activeUser,
        ]);
        // Log task creation
        TaskLog::create([
            'task_id' => $task->task_id,
            'log_description' => 'Task created',
            'added_by' =>  $activeUser,
        ]);
        // Handle file uploads
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $document) {
                $fileName = $this->storeFile($document);

                TaskMedia::create([
                    'task_id' => $task->task_id,
                    'category' => 'document',
                    'file_name' => $fileName,
                    'created_by' =>  $activeUser,
                ]);

                // Log document upload
                TaskLog::create([
                    'task_id' => $task->task_id,
                    'log_description' => 'Document uploaded: ' . $fileName,
                    'added_by' =>  $activeUser,
                ]);
            }
        }
        return response()->json([
            'status' => 'success',
            'messages' => 'Task created successfully!'
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


    public function update(Request $request, PersonalTask $task)
    {
        // $this->authorize('update', $task);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:todo,in_progress,completed',
            'time_estimate' => 'nullable|integer|min:0',
            'is_habit' => 'boolean',
            'habit_frequency' => 'nullable|in:daily,weekly,monthly,weekdays',
            'okr' => 'nullable|string',
        ]);

        if ($request->category === '_new_category') {
            $validated['category'] = $request->new_category_name;
        }

        $task->update($validated);
        return redirect()->back()->with('success', 'Task updated successfully');
    }

    public function updateStatus(Request $request, PersonalTask $task)
    {
        // $this->authorize('update', $task);
        $request->validate([
            'status' => 'required|in:todo,in_progress,completed',
        ]);

        $task->update(['status' => $request->status]);
        return response()->json(['success' => true]);
    }

    public function destroy(PersonalTask $task)
    {
        // $this->authorize('delete', $task);
        $task->delete();
        return redirect()->back()->with('success', 'Task deleted successfully');
    }

    public function getDocuments($taskId)
    {
        $documents = TaskMedia::where('task_id', $taskId)->get();
        return response()->json($documents);
    }

    public function uploadDocument(Request $request, $taskId)
    {
        $request->validate([
            'document' => 'required|file|max:10240', // 10MB max
            'description' => 'nullable|string'
        ]);

        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('task-documents');

            $document = TaskMedia::create([
                'task_id' => $taskId,
                'file_name' => $request->file('document')->getClientOriginalName(),
                'file_path' => $path,
                'file_type' => $request->file('document')->getMimeType(),
                'file_size' => $request->file('document')->getSize(),
                'description' => $request->input('description')
            ]);

            return response()->json(['success' => true, 'document' => $document]);
        }

        return response()->json(['success' => false, 'error' => 'No file uploaded']);
    }
}
