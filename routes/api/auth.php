<?php

use App\Http\Controllers\Api\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('auth/register', [AuthController::class, 'register'])
    ->middleware('guest')
    ->name('register');

Route::post('auth/login', [AuthController::class, 'login'])
    ->middleware('guest')
    ->name('login');

Route::post('auth/logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum')
    ->name('logout');
