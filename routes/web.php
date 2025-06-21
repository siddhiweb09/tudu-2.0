<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DemoController;


Route::get('/', function () {
    return view('welcome');
});

Route::match(['get', 'post'], '/demo', [DemoController::class, 'demoIndex'])->name('demo');
