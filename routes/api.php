<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BookController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['api', 'throttle:10,1'], 'prefix' => 'auth'], function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::group(['middleware' => ['api', 'auth:api'], 'prefix' => 'auth'], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('/profile', [AuthController::class, 'profile']);
    Route::get('/book', [BookController::class, 'index']);
    Route::post('/book', [BookController::class, 'store']);
    Route::post('/book/{id}', [BookController::class, 'update']);
    Route::get('/book/{id}', [BookController::class, 'show']);
    Route::delete('/book/{id}', [BookController::class, 'destroy']);
});
