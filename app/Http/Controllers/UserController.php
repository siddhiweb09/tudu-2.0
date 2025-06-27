<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\DelegatedTask;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function getDepartments()
    {
        $departments = User::select('department')
            ->distinct()
            ->whereNotNull('department')
            ->pluck('department');

        return response()->json($departments);
    }
    public function getUsersByDepartment($department)
    {
        $users = User::where('department', $department)->select('employee_name', 'employee_code')->get();

        return response()->json($users);
    }

    public function getTaskVisibilityUsers($task_id)
    {
        $currentUser = Auth::user()->employee_code . '*' . Auth::user()->employee_name;

        // Fetch assign_to from first table
        $assignedFromFirst = Task::where('task_id', $task_id)
            ->where('assign_by', $currentUser)
            ->pluck('assign_to');

        // Fetch assign_to from second table
        $assignedFromSecond = DelegatedTask::where('task_id', $task_id)
            ->where('assign_by', $currentUser)
            ->pluck('assign_to');

        // Merge, filter nulls, remove duplicates
        $mergedAssignedUsers = $assignedFromFirst
            ->merge($assignedFromSecond)
            ->filter()
            ->unique()
            ->values();

        return response()->json($mergedAssignedUsers);
    }

    public function project(){
        return view('projects');
    }
}
