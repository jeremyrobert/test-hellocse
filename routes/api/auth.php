<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->name('api.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::post('register', [AuthController::class, 'register'])->name('register');
        Route::post('login', [AuthController::class, 'login'])->name('login');
    });

    Route::get('refresh-token', [AuthController::class, 'refreshToken'])
        ->middleware('auth:sanctum', 'ability:'.TokenAbility::REFRESH_TOKEN->value)
        ->name('refresh-token');

    Route::post('logout', [AuthController::class, 'logout'])
        ->middleware('auth:sanctum')
        ->name('logout');
});
