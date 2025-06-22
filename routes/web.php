<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DemoController;
use App\Http\Controllers\LoginController;

Route::get('/', function () {
    return view('dashboard'); 
})->name('dashboard');

Route::get('/login', function () {
    return view('login');
});

Route::post('/login', [LoginController::class, 'authenticate'])->name('login');

Route::match(['get', 'post'], '/my-todo-list', [DemoController::class, 'demoIndex'])->name('demo');
