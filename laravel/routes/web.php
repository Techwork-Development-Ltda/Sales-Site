<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\LoginController;
use App\Http\Controllers\Web\HomeController;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/', function () {
//     return class_exists(\App\Http\Web\Controllers\HomeController::class) ? 'ok' : 'faltou';
// });


Route::get('/', [HomeController::class, 'homeView']);