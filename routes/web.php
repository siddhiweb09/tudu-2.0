<?php

use App\Http\Controllers\SupportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DemoController;
use App\Http\Controllers\PersonalTaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TaskController;
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
    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::match(['get', 'post'], '/all-tasks', [TaskController::class, 'allTask'])->name('tasks.allTasks');

    // Tasks
    Route::get('/tasks-calender', function () {
        return view('tasks.calender');
    })->name('tasks.calender');
    Route::match(['get', 'post'], '/add-task', [TaskController::class, 'store'])->name('tasks.store');

    // Delegated Tasks
    Route::match(['get', 'post'], '/delegate-tasks/{id}', [TaskController::class, 'delegateTask'])->name('tasks.delegate');

    // Support
    Route::match(['get', 'post'], '/support', [TaskController::class, 'helpAndSupport'])->name('helpAndSupport');
    Route::post('/store-support-ticket', [TaskController::class, 'storeSupportForm'])->name('storeSupportForm');
    Route::get('/support', function () {
        return view('helpAndSupport');
    })->name('helpAndSupport');
    Route::post('/store-support-ticket', [SupportController::class, 'storeSupportForm'])->name('storeSupportForm');
    // Personal Tasks
    Route::get('/personal-tasks', [PersonalTaskController::class, 'index'])->name('personal-tasks.index');
    Route::get('/personal-tasks/list', function () {
        return redirect()->route('personal-tasks.index', ['view' => 'list']);
    })->name('personal-tasks.list');
    Route::get('/personal-tasks/kanban', function () {
        return redirect()->route('personal-tasks.index', ['view' => 'kanban']);
    })->name('personal-tasks.kanban');
    Route::get('/personal-tasks/calendar', function () {
        return redirect()->route('personal-tasks.index', ['view' => 'calendar']);
    })->name('personal-tasks.calendar');
    Route::get('/personal-tasks/matrix', function () {
        return redirect()->route('personal-tasks.index', ['view' => 'matrix']);
    })->name('personal-tasks.matrix');

    Route::post('/personal-tasks', [PersonalTaskController::class, 'store'])->name('personal-tasks.store');
    Route::put('/personal-tasks/{task}', [PersonalTaskController::class, 'update'])->name('personal-tasks.update');
    Route::put('/personal-tasks/{task}/status', [PersonalTaskController::class, 'updateStatus'])->name('personal-tasks.update-status');
    Route::delete('/personal-tasks/{task}', [PersonalTaskController::class, 'destroy'])->name('personal-tasks.destroy');

    // Timer
    Route::post('/personal-tasks/start-timer', [PersonalTaskController::class, 'startTimer'])->name('personal-tasks.start-timer');
    Route::post('/personal-tasks/stop-timer', [PersonalTaskController::class, 'stopTimer'])->name('personal-tasks.stop-timer');

    // Task Documents
    // Route::get('/personal-tasks/{task}/documents', [TaskDocumentController::class, 'index'])->name('task-documents.index');
    // Route::post('/personal-tasks/{task}/documents', [TaskDocumentController::class, 'store'])->name('task-documents.store');
    // Route::get('/documents/{document}', [TaskDocumentController::class, 'show'])->name('task-documents.show');
    // Route::put('/documents/{document}', [TaskDocumentController::class, 'update'])->name('task-documents.update');
    // Route::delete('/documents/{document}', [TaskDocumentController::class, 'destroy'])->name('task-documents.destroy');

    // Task Time
    // Route::get('/personal-tasks/{task}/time', [TaskTimeController::class, 'index'])->name('task-time.index');

    //fetch Functions
    Route::get('/get-task-details/{id}', [TaskController::class, 'getTaskById']);
    Route::get('/get-departments', [UserController::class, 'getDepartments']);
    Route::get('/get-users-by-department/{department}', [UserController::class, 'getUsersByDepartment']);
    Route::get('/get-task-visibility/{task_id}', [UserController::class, 'getTaskVisibilityUsers']);

});

Route::match(['get', 'post'], '/demo', [DemoController::class, 'demoIndex'])->name('demo');
