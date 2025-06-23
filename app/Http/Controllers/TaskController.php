<?php
// app/Http/Controllers/TaskController.php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\SupportTicket;

use Illuminate\Support\Facades\Validator;
use App\Models\Task;
use App\Models\TaskItem;
use App\Models\TaskMedia;
use App\Models\TaskLog;
use App\Models\TaskReminder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    public function store(Request $request)
    {
        // Decode JSON inputs
        $tasks = json_decode($request->tasks_json, true) ?? [];
        $links = json_decode($request->links_json, true) ?? [];
        $reminders = json_decode($request->reminders_json, true) ?? [];
        $frequencyDuration = json_decode($request->frequency_duration_json, true) ?? [];

        // Validate the main task data
        $validator = Validator::make($request->all(), [
            'task_title' => 'required|string|max:255',
            'task_description' => 'required|string',
            'category' => 'required|string|max:255',
            'assign_to' => 'required|string',
            'due_date' => 'nullable|string',
            'priority' => 'required|in:high,medium,low',
            'tasks_json' => 'json',
            'voice_notes' => 'nullable|string',
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
            'assign_by' => auth()->id(),
        ]);



        // Log task creation
        TaskLog::create([
            'task_id' => $task->id,
            'log_description' => 'Task created',
            'added_by' => auth()->id()
        ]);

        // Handle file uploads
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $document) {
                $fileName = $this->storeFile($document);

                TaskMedia::create([
                    'task_id' => $task->id,
                    'category' => 'document',
                    'file_name' => $fileName,
                    'created_by' => auth()->id()
                ]);

                // Log document upload
                TaskLog::create([
                    'task_id' => $task->id,
                    'log_description' => 'Document uploaded: ' . $fileName,
                    'added_by' => auth()->id()
                ]);
            }
        }

        // Handle voice note
        if ($request->has('voice_notes') && $request->voice_notes) {
            $fileName = $this->storeVoiceNote($request->voice_notes);

            TaskMedia::create([
                'task_id' => $task->id,
                'category' => 'voice_note',
                'file_name' => $fileName,
                'created_by' => auth()->id()
            ]);

            // Log voice note upload
            TaskLog::create([
                'task_id' => $task->id,
                'log_description' => 'Voice note recorded',
                'added_by' => auth()->id()
            ]);
        }

        // Handle links
        if ($request->has('links')) {
            foreach ($request->links as $link) {
                TaskMedia::create([
                    'task_id' => $task->id,
                    'category' => 'link',
                    'file_name' => $link,
                    'created_by' => auth()->id()
                ]);

                // Log link addition
                TaskLog::create([
                    'task_id' => $task->id,
                    'log_description' => 'Link added: ' . $link,
                    'added_by' => auth()->id()
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
        $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();

        // Store file on remote server
        $file->move('//todo.isbmerp.co.in/uploads', $fileName);

        return $fileName;
    }

    private function storeVoiceNote($base64Audio)
    {
        try {
            // Extract the base64 data
            $audioData = substr($base64Audio, strpos($base64Audio, ',') + 1);
            $audioData = base64_decode($audioData);

            // Generate a unique filename
            $fileName = 'voice_' . time() . '_' . Str::random(10) . '.wav';

            // Store the file on remote server
            file_put_contents('//todo.isbmerp.co.in/uploads/' . $fileName, $audioData);

            return $fileName;
        } catch (\Exception $e) {
            \Log::error('Voice note storage failed: ' . $e->getMessage());
            return null;
        }
    }

    public function allTask()
    {
        return view('tasks.allTasks');
    }

    public function taskCalender()
    {
        return view('tasks.calender');
    }

}
