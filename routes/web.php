<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DemoController;
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

    Route::match(['get', 'post'], '/tasks-calender', [TaskController::class, 'taskCalender'])->name('tasks.calender');

    Route::match(['get', 'post'], '/support', [TaskController::class, 'helpAndSupport'])->name('helpAndSupport');

    Route::post('/store-support-ticket', [TaskController::class, 'storeSupportForm'])->name('storeSupportForm');
});
