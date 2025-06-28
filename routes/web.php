<?php

use App\Http\Controllers\SupportController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DemoController;
use App\Http\Controllers\PersonalTaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use App\Models\User;

use Illuminate\Console\View\Components\Task;

// Login page (GET)
Route::get('/login', function () {
    return view('login');
})->name('login');

// Login authentication (POST only is best practice)
Route::post('/authenticate', [LoginController::class, 'authenticate'])->name('authenticate');

// Demo route (GET or POST if needed)
Route::match(['get', 'post'], '/demo', [DemoController::class, 'demoIndex'])->name('demo');

Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected routes (only accessible after login)
Route::middleware(['auth:web'])->group(function () {
    // Dashboard
    Route::get('/', [TaskController::class, 'dashboard'])->name('dashboard');

    Route::post('/users/profile-pictures', function (Request $request) {
        $codes = $request->input('codes', []);

        // Validate input
        if (empty($codes)) {
            return response()->json(['error' => 'No employee codes provided'], 400);
        }

        // Fetch users with their profile pictures
        $users = User::whereIn('employee_code', $codes)
            ->select('employee_code', 'profile_picture')
            ->get()
            ->mapWithKeys(function ($user) {
                return [
                    $user->employee_code => $user->profile_picture
                        ? asset('storage/' . $user->profile_picture)
                        : asset('assets/images/profile_picture/user.jpg')
                ];
            });

        return response()->json($users);
    });


    Route::get('/profile/{user_id}', [UserController::class, 'userProfile'])->name('profile');
    Route::post('/user/change-picture/{user_id}', [UserController::class, 'changePicture'])
        ->where('id', '.*')
        ->name('user.changepicture');

    Route::get('/pending-tasks', function () {
        return view('tasks.pendingTask');
    })->name('tasks.pendingTask');
    Route::get('/in-process-tasks', function () {
        return view('tasks.inProcessTask');
    })->name('tasks.inProcessTask');
    Route::get('/in-review-tasks', function () {
        return view('tasks.inReviewTask');
    })->name('tasks.inReviewTask');
    Route::get('/overdue-tasks', function () {
        return view('tasks.overdueTask');
    })->name('tasks.overdueTask');

    Route::match(['get', 'post'], '/all-task', [TaskController::class, 'allTask'])->name('tasks.allTasks');
    Route::match(['get', 'post'], '/all-tasks', [TaskController::class, 'allTask'])->name('tasks.allTask');

    Route::get('/tasks/{type}', [TaskController::class, 'getTasksByType']);
    Route::get('/fetch-user-tasks/{id}', [TaskController::class, 'getUserTasks']);
    // Tasks
    Route::get('/tasks-calender', function () {
        return view('tasks.calender');
    })->name('tasks.calender');
    Route::match(['get', 'post'], '/add-task', [TaskController::class, 'store'])->name('tasks.store');
    Route::match(['get', 'post'], '/task/{task_id}', [TaskController::class, 'taskDetails'])->name('tasks.taskDetails');
    Route::match(['get', 'post'], '/add-comment', [TaskController::class, 'addComment'])->name('addComment');


    // Delegated Tasks
    Route::match(['get', 'post'], '/delegate-tasks/{id}', [TaskController::class, 'delegateTask'])->name('tasks.delegate');
    Route::post('/store-delegate-task', [TaskController::class, 'storeDelegateForm'])->name('storeDelegateForm');

    // Support
    Route::match(['get', 'post'], '/support', [TaskController::class, 'helpAndSupport'])->name('helpAndSupport');
    Route::post('/store-support-ticket', [TaskController::class, 'storeSupportForm'])->name('storeSupportForm');
    Route::get('/support', function () {
        return view('helpAndSupport');
    })->name('helpAndSupport');
    Route::post('/store-support-ticket', [SupportController::class, 'storeSupportForm'])->name('storeSupportForm');

    // Personal Tasks
    Route::get('/personal-tasks', [PersonalTaskController::class, 'index'])->name('personal-tasks.index');
    Route::get('/personal-tasks-kanban', [PersonalTaskController::class, 'kanban'])->name('personal-tasks.kanban');
    Route::get('/personal-tasks-calendar', [PersonalTaskController::class, 'calendar'])->name('personal-tasks.calendar');
    Route::get('/personal-tasks-matrix', [PersonalTaskController::class, 'matrix'])->name('personal-tasks.matrix');
    Route::get('/personal-tasks-show/{task:task_id}', [PersonalTaskController::class, 'show'])->name('personal-tasks.show');
    Route::post('/add-personal-tasks', [PersonalTaskController::class, 'store'])->name('personal-tasks.store');

    // Notes routes
    Route::post('/add-personal-tasks-notes', [PersonalTaskController::class, 'addNote'])->name('personal-tasks.add-note');
    Route::post('/edit-personal-tasks-notes', [PersonalTaskController::class, 'editNote'])->name('personal-tasks.update-note');
    Route::post('/delete-personal-tasks-notes', [PersonalTaskController::class, 'deleteNote'])->name('personal-tasks.delete-note');

    // Document routes
    Route::get('/personal-tasks/{task}/documents', [PersonalTaskController::class, 'getDocuments'])->name('personal-tasks.documents');
    Route::post('/personal-tasks/{task}/documents/upload', [PersonalTaskController::class, 'uploadDocument'])->name('personal-tasks.upload-document');
    Route::post('/personal-tasks-delete-document', [PersonalTaskController::class, 'deleteDocument'])->name('personal-tasks.delete-document');

    Route::match(['get', 'post'], '/personal-tasks/{task}', [PersonalTaskController::class, 'update'])->name('personal-tasks.update');
    Route::match(['get', 'post'], '/personal-tasks/{task}/status', [PersonalTaskController::class, 'updateStatus'])->name('personal-tasks.update-status');
    Route::delete('/personal-tasks/{task}', [PersonalTaskController::class, 'destroy'])->name('personal-tasks.destroy');

    //fetch Functions
    Route::get('/get-task-details/{id}', [TaskController::class, 'getTaskById']);
    Route::get('/get-departments', [UserController::class, 'getDepartments']);
    Route::get('/get-users-by-department/{department}', [UserController::class, 'getUsersByDepartment']);
    Route::get('/get-task-visibility/{task_id}', [UserController::class, 'getTaskVisibilityUsers']);
    Route::get('/get-team-members/{code}', [TeamController::class, 'getMembers']);


    // Personal Tasks Fetch Functions
    Route::get('/fetch-personal-tasks', [PersonalTaskController::class, 'fetchTasks'])->name('tasks.fetch');

    // Kanban Cards Status Change in Personal
    Route::put('/update-status/{id}', [PersonalTaskController::class, 'updateKanbanStatus']);


    //teams 
    Route::match(['get', 'post'], 'teams', [TeamController::class, 'teams'])->name('team.viewTeams');
    Route::post('/create-team', [TeamController::class, 'createTeam'])->name('store.createTeam');
    Route::post('/delete-team', [TeamController::class, 'deleteTeam'])->name('teams.delete');
    Route::get('/fetch-team-data/{code}', [TeamController::class, 'fetchTeamData'])->name('teams.fetchTeamData');
    Route::post('/update-team', [TeamController::class, 'updateTeam'])->name('teams.update');


    Route::get('/projects', [UserController::class, 'project']);
});

Route::match(['get', 'post'], '/demo', [DemoController::class, 'demoIndex'])->name('demo');
