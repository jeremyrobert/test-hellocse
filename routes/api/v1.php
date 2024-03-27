<?php

use App\Http\Controllers\Api\V1\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->name('api.profiles.')->group(function () {
    Route::post('profiles', [ProfileController::class, 'store'])->name('store');
    Route::match(['put', 'patch', 'post'], 'profiles/{profile}', [ProfileController::class, 'update'])->where('profile', '[0-9]+')->name('update');
    Route::delete('profiles/{profile}', [ProfileController::class, 'destroy'])->where('profile', '[0-9]+')->name('destroy');
});
