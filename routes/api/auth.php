<?php

use App\Http\Controllers\Api\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::post('auth/register', [AuthController::class, 'register'])->name('register');
    Route::post('auth/login', [AuthController::class, 'login'])->name('login');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('auth/refresh-token', [AuthController::class, 'refreshToken'])->name('refresh-token');
    Route::post('auth/logout', [AuthController::class, 'logout'])->name('logout');
});
