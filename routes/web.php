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

Route::match(['get', 'post'], '/authenticate', [LoginController::class, 'authenticate'])->name('authenticate');

Route::match(['get', 'post'], '/demo', [DemoController::class, 'demoIndex'])->name('demo');
