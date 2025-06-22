<?php

namespace App\Http\Controllers;

use App\Models\PersonalTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PersonalTaskController extends Controller
{
    public function index(Request $request)
    {
        $view = $request->query('view', 'list');
        $user = Auth::user();
        
        $tasks = PersonalTask::where('assign_by', 'sanket')
            ->orderBy('due_date', 'asc')
            ->get();
            
        $ai_suggestions = $this->getAiSuggestions($tasks);
        
        // $active_timer = $this->getActiveTimer($user);
        
        return view('personal-tasks.index', [
            'view' => $view,
            'tasks' => $tasks,
            'ai_suggestions' => $ai_suggestions,
            'active_timer' => 1,
        ]);
    }
    
    public function listView()
    {
        $user = Auth::user();
        $tasks = PersonalTask::where('assign_by', $user->id)
            ->orderBy('due_date', 'asc')
            ->get();
            
        return view('personal-tasks.list', ['tasks' => $tasks]);
    }
    
    public function kanbanView()
    {
        $user = Auth::user();
        $tasks = PersonalTask::where('assign_by', $user->id)
            ->orderBy('due_date', 'asc')
            ->get();
            
        return view('personal-tasks.kanban', ['tasks' => $tasks]);
    }
    
    public function calendarView(Request $request)
    {
        $month = $request->query('month', date('n'));
        $year = $request->query('year', date('Y'));
        
        $user = Auth::user();
        $tasks = PersonalTask::where('assign_by', $user->id)
            ->whereMonth('due_date', $month)
            ->whereYear('due_date', $year)
            ->orderBy('due_date', 'asc')
            ->get();
            
        return view('personal-tasks.calendar', [
            'tasks' => $tasks,
            'month' => $month,
            'year' => $year,
        ]);
    }
    
    public function matrixView()
    {
        $user = Auth::user();
        $tasks = PersonalTask::where('assign_by', $user->id)
            ->where('status', '!=', 'completed')
            ->orderBy('due_date', 'asc')
            ->get();
            
        return view('personal-tasks.matrix', ['tasks' => $tasks]);
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high',
            'time_estimate' => 'nullable|integer|min:0',
            'is_habit' => 'boolean',
            'habit_frequency' => 'nullable|in:daily,weekly,monthly,weekdays',
            'okr' => 'nullable|string',
        ]);
        
        $validated['assign_by'] = Auth::id();
        
        if ($request->category === '_new_category') {
            $validated['category'] = $request->new_category_name;
            // You might want to store the color as well
        }
        
        PersonalTask::create($validated);
        
        return redirect()->back()->with('success', 'Task added successfully');
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
    
    private function getAiSuggestions($tasks)
    {
        // Implement your AI suggestion logic here
        // This is just a placeholder
        $suggestions = [];
        
        $incompleteTasks = $tasks->where('status', '!=', 'completed');
        $overdueTasks = $incompleteTasks->filter(function($task) {
            return $task->due_date && $task->due_date->isPast();
        });
        
        if ($overdueTasks->count() > 0) {
            $suggestions[] = "You have " . $overdueTasks->count() . " overdue tasks. Consider prioritizing these first.";
        }
        
        if ($incompleteTasks->count() > 10) {
            $suggestions[] = "You have a large number of incomplete tasks (" . $incompleteTasks->count() . "). Consider delegating or breaking them down.";
        }
        
        return $suggestions;
    }
}