<?php

use App\Http\Controllers\Api\ArticleApiController;
use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\UserApiController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->prefix('articles')->group(function () {
    Route::get('/', [ArticleApiController::class, 'index']);
    Route::post('/', [ArticleApiController::class, 'store']);
    Route::get('{id}', [ArticleApiController::class, 'show']);
    Route::put('{id}', [ArticleApiController::class, 'update']);
    Route::delete('{id}', [ArticleApiController::class, 'destroy']);
    Route::get('/articles/{id}/pdf', [ArticleApiController::class, 'downloadPdf']);
});

    Route::get('/users', [UserApiController::class, 'index']);

Route::middleware('auth:sanctum')->prefix('categories')->group(function () {

    Route::get('/', [CategoryApiController::class, 'index']);
    Route::post('/', [CategoryApiController::class, 'store']);
    Route::get('{id}', [CategoryApiController::class, 'show']);
    Route::put('{id}', [CategoryApiController::class, 'update']);
    Route::delete('{id}', [CategoryApiController::class, 'destroy']);

});


