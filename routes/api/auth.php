<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::post('auth/register', [AuthController::class, 'register'])->name('register');
    Route::post('auth/login', [AuthController::class, 'login'])->name('login');
});

Route::get('auth/refresh-token', [AuthController::class, 'refreshToken'])
    ->middleware('auth:sanctum', 'ability:'.TokenAbility::REFRESH_TOKEN->value)
    ->name('refresh-token');

Route::post('auth/logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum')
    ->name('logout');
