<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CacheController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\ApiAuthenticate;

//login
Route::post('/login', [AuthController::class, 'login']);
//Authentication required
Route::middleware([ApiAuthenticate::class])->group(function () {
    //Login
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user/self', [AuthController::class, 'checkAuth']);
    //User
    Route::post('v1/user', [UserController::class, 'insertUser']);
    Route::get('v1/user/{id}', [UserController::class, 'getUserById']);
    Route::put('v1/user/{id}', [UserController::class, 'putUserById']);
    Route::delete('v1/user/{id}', [UserController::class, 'deleteUserById']);
    Route::patch('v1/user/{id}', [UserController::class, 'patchUserById']);
    //Cache
    Route::post('/cache', [CacheController::class, 'store']);
    Route::get('/cache/{key}', [CacheController::class, 'show']);
    Route::delete('/cache/{key}', [CacheController::class, 'destroy']);
});
    

?>