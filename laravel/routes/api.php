<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\AuthController;

//login
Route::post('/login', [AuthController::class, 'login']);

//Necessário autenticação
Route::middleware(['auth:api'])->group(function () {
    //Login
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user/self', [AuthController::class, 'consultarLogin']);

    //usuarios
    Route::post('/user', [UserController::class, 'insertUser']);
    Route::get('/user/{id}', [UserController::class, 'getUserById']);
    Route::put('/user/{id}', [UserController::class, 'putUserById']);
    Route::delete('/user/{id}', [UserController::class, 'deleteUserById']);
    Route::patch('/user/{id}', [UserController::class, 'patchUserById']);
});


?>