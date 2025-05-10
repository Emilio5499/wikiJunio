<?php

use Illuminate\Routing\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/articles');
});
