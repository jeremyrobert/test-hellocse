<?php

use App\Http\Controllers\Api\V1\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::name('api.profile.')->group(function () {
        Route::post('profile', [ProfileController::class, 'store'])->name('store');
    });
});
