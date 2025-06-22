<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DemoController;
use App\Http\Controllers\PersonalTaskController;



Route::get('/', function () {
    return view('dashboard');
});

Route::get('/login', function () {
    return view('login');
});

Route::get('/my-todo-list', function () {
    return view('');
});



Route::match(['get', 'post'], '/demo', [DemoController::class, 'demoIndex'])->name('demo');

 // Personal Tasks
    Route::get('/personal-tasks', [PersonalTaskController::class, 'index'])->name('personal-tasks.index');
    Route::get('/personal-tasks/list', [PersonalTaskController::class, 'listView'])->name('personal-tasks.list');
    Route::get('/personal-tasks/kanban', [PersonalTaskController::class, 'kanbanView'])->name('personal-tasks.kanban');
    Route::get('/personal-tasks/calendar', [PersonalTaskController::class, 'calendarView'])->name('personal-tasks.calendar');
    Route::get('/personal-tasks/matrix', [PersonalTaskController::class, 'matrixView'])->name('personal-tasks.matrix');
    
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
