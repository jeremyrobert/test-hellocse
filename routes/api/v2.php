<?php

use App\Enums\TokenAbility;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum', 'ability:'.TokenAbility::ACCESS_API->value)->group(function () {
    Route::get('/', function () {
        return response()->json([
            'message' => 'Nothing to see here. Move along.',
        ]);
    });
});
