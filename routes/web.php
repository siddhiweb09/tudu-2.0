<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DemoController;


Route::get('/', function () {
    return view('dashboard');
});

Route::get('/login', function () {
    return view('login');
});


Route::match(['get', 'post'], '/demo', [DemoController::class, 'demoIndex'])->name('demo');
