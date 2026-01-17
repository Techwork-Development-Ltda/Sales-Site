<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\LoginController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Middleware\WebAuthenticate;

Route::get('/', function () {return view('welcome');})->name('welcome');
Route::get('/login', [LoginController::class, 'loginView'])->name('login');
Route::post('/login', [LoginController::class, 'loginAttempt'])->name('login.attempt');
//Authentication required
Route::middleware('web.stack')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('auth.logout');
    Route::get('/home', [HomeController::class, 'homeView'])->name('home');
});