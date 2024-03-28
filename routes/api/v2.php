<?php

use App\Http\Middleware\CheckTokenAbilities;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', CheckTokenAbilities::class])->group(function () {
    Route::get('/', function () {
        return response()->json([
            'message' => 'Nothing to see here. Move along.',
        ]);
    });
});
