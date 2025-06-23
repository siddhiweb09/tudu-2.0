<?php

namespace App\Http\Controllers;

use App\Models\PersonalTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TaskMedia;
use App\Models\TaskLog;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class PersonalTaskController extends Controller
{
    // public function index(Request $request)
    // {
    //     $view = $request->query('view', 'list');
    //     $user = Auth::user();

    //     $tasks = PersonalTask::where('assign_by',  $user->id)
    //         ->orderBy('due_date', 'asc')
    //         ->get();

    //     return view('personal-tasks.index', [
    //         'view' => $view,
    //         'tasks' => $tasks,
    //     ]);
    // }

    public function index(Request $request)
    {
        $view = $request->query('view', 'list');
        $user = Auth::user();

        $baseQuery = PersonalTask::where('assign_by', $user->id);
        $month = $request->query('month', date('n'));
        $year = $request->query('year', date('Y'));

        // View-specific query modifications
        switch ($view) {
            case 'calendar':
                $baseQuery->where(function ($query) use ($month, $year) {
                    $query->whereMonth('due_date', $month)
                        ->whereYear('due_date', $year);
                });
                break;
            case 'matrix':
                $baseQuery->where('status', '!=', 'completed');
                break;
        }

        $tasks = $baseQuery->orderBy('due_date', 'asc')->get();

        if ($request->ajax()) {
            return response()->json([
                'html' => view("personal-tasks.{$view}", compact('tasks', 'view', 'month', 'year'))->render()
            ]);
        }

        return view('personal-tasks.index', compact('view', 'tasks', 'month', 'year'));
    }

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
            'due_date' => 'nullable|string',
            'priority' => 'required|in:high,medium,low',
            'documents' => 'nullable|array',
            'documents.*' => 'file|max:10240', // 10MB max
            'reminders_json' => 'json',
            'frequency' => 'required_if:recurring,on',
            // 'frequency_duration_json' => 'json',
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
            // 'frequency_duration' => $request->frequency_duration_json,
            'reminders' => $request->reminders_json,
            'links' => $request->links_json,
            'status' => 'Pending',
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
    public function show(PersonalTask $task)
    {
        // $this->authorize('view', $task);

        return response()->json($task);
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

    // public function startTimer(Request $request)
    // {
    //     $validated = $request->validate([
    //         'task_id' => 'required|exists:personal_tasks,id',
    //         'focus_duration' => 'nullable|integer|min:0',
    //     ]);

    //     $task = PersonalTask::find($validated['task_id']);
    //     $this->authorize('update', $task);

    //     // Stop any existing timer
    //     $this->stopTimer(Auth::user());

    //     // Start new timer
    //     $timeLog = new TaskTimeLog([
    //         'assign_by' => Auth::id(),
    //         'task_id' => $validated['task_id'],
    //         'start_time' => now(),
    //     ]);

    //     if ($validated['focus_duration'] > 0) {
    //         $timeLog->end_time = now()->addMinutes($validated['focus_duration']);
    //     }

    //     $timeLog->save();

    //     return redirect()->back()->with('success', 'Timer started');
    // }

    // public function stopTimer(Request $request)
    // {
    //     $user = Auth::user();
    //     $activeTimer = $this->getActiveTimer($user);

    //     if ($activeTimer) {
    //         $activeTimer->update([
    //             'end_time' => now(),
    //             'duration' => $activeTimer->start_time->diffInMinutes(now()),
    //         ]);

    //         return redirect()->back()->with('success', 'Timer stopped');
    //     }

    //     return redirect()->back()->with('error', 'No active timer found');
    // }

    // private function getActiveTimer($user)
    // {
    //     return TaskTimeLog::where('assign_by', $user->id)
    //         ->whereNull('end_time')
    //         ->first();
    // }

}
