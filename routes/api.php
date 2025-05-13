<?php

use App\Http\Controllers\Api\ArticleApiController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/articles', [ArticleApiController::class, 'store']);
});

Route::get('/articles/{id}/pdf', [ArticleApiController::class, 'downloadPdf']);
