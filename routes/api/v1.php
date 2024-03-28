<?php

use App\Http\Controllers\Api\V1\CommentController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Middleware\CheckTokenAbilities;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', CheckTokenAbilities::class])
    ->name('api.profiles.')
    ->group(function () {
        Route::post('profiles', [ProfileController::class, 'store'])->name('store');
        Route::match(['put', 'patch', 'post'], 'profiles/{profile}', [ProfileController::class, 'update'])->where('profile', '[0-9]+')->name('update');
        Route::delete('profiles/{profile}', [ProfileController::class, 'destroy'])->where('profile', '[0-9]+')->name('destroy');

        Route::post('profiles/{profile}/comments', [CommentController::class, 'store'])->where('profile', '[0-9]+')->name('comments.store');
    });

// Dynamic middleware based on request header (to show the status of the profile or not => ProfileResource)
Route::middleware(Request::header('Authorization') ? ['auth:sanctum', CheckTokenAbilities::class] : [])
    ->name('api.profiles.')
    ->group(function () {
        Route::get('profiles', [ProfileController::class, 'index'])->where('profile', '[0-9]+')->name('index');
    });
