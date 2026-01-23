<?php

use App\Http\Controllers\Api\ArticleApiController;
use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\UserApiController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/articles', [ArticleApiController::class, 'store']);
});

Route::get('/articles/{id}/pdf', [ArticleApiController::class, 'downloadPdf']);

Route::middleware('auth:sanctum')->prefix('articles')->group(function () {
    Route::get('/', [ArticleApiController::class, 'index']);
    Route::post('/', [ArticleApiController::class, 'store']);
    Route::get('{id}', [ArticleApiController::class, 'show']);
    Route::put('{id}', [ArticleApiController::class, 'update']);
    Route::delete('{id}', [ArticleApiController::class, 'destroy']);
});

Route::get('/users', [UserApiController::class, 'index']);

Route::get('/categories', [CategoryApiController::class, 'index']);
