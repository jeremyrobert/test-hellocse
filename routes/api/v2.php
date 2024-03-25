<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'Nothing to see here. Move along.',
    ]);
});

include __DIR__.'/auth.php';
