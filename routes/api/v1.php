<?php

use App\Enums\TokenAbility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum', 'ability:'.TokenAbility::ACCESS_API->value)->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
