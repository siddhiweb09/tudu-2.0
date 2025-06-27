<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\DelegatedTask;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function userProfile($id)
    {
        $proUser = User::findOrFail($id);

        // $user = Auth::user();
        $usercode = $proUser->employee_code . '*' . $proUser->employee_name;

        // Step 1: Main tasks assigned to or by user
        $mainTasks = Task::where(function ($query) use ($usercode) {
            $query->where('assign_to', $usercode)
                ->orWhere('assign_by', $usercode);
        })->get()->each(function ($task) {
            $task->flag = 'Main';
        });

        // Step 2: Direct delegated tasks
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

        // Step 3: Delegated tasks related to main tasks and visible
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

        // Step 5: Calculate completion percentage
        $completedTasks = $allTasks->filter(function ($task) {
            return strtolower($task->final_status) === 'completed';
        });

        $totalTasksCount = $allTasks->count();
        $completedTasksCount = $completedTasks->count();

        $completionPercentage = $totalTasksCount > 0
            ? round(($completedTasksCount / $totalTasksCount) * 100)
            : 0;

        // Step 6: Count distinct project names
        $projectCount = $allTasks->pluck('project_name')
            ->filter() // removes null/empty
            ->unique()
            ->count();

        // Return to profile view
        return view('profile', [
            'proUser' => $proUser,
            'taskCompletion' => $completionPercentage,
            'totalProjects' => $projectCount,
            'taskCount' => $totalTasksCount,
            'completedTaskCount' => $completedTasksCount,
        ]);

        // return view('profile', compact('proUser'));
    }

    public function changePicture($id, Request $request)
    {
        $user = User::where('id', $id)->first();

        if (!$user) {
            return response()->json(['error' => 'user not found'], 404);
        }

        if (!$request->hasFile('profile_picture')) {
            return response()->json(['error' => 'No profile picture uploaded'], 400);
        }

        $file = $request->file('profile_picture');

        // Validate the file type
        $allowedExtensions = ['jpeg', 'jpg', 'png', 'gif', 'webp'];
        $extension = $file->getClientOriginalExtension();

        if (!in_array(strtolower($extension), $allowedExtensions)) {
            return response()->json(['error' => 'Unsupported image format'], 400);
        }

        // Directory to save the image
        $imageDir = public_path("assets/images/profile_picture");
        if (!file_exists($imageDir)) {
            mkdir($imageDir, 0777, true);
        }

        $imageName = str_replace(' ', '-', $user->user_print_name) . '.' . $extension;
        $file->move($imageDir, $imageName);

        // Save filename in database
        $user->profile_picture = $imageName;
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Profile Picture Uploaded',
            'file_name' => $imageName
        ], 200);
    }

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

    public function project()
    {
        return view('projects');
    }
}
