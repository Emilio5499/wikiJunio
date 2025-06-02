<?php

use App\Http\Controllers\Api\ArticleApiController;
use App\Http\Controllers\ArticlePdfController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicArticleController;
use App\Http\Livewire\ArticleCrud;
use Illuminate\Support\Facades\Route;
use App\Models\Article;

Route::get('/', [PublicArticleController::class, 'index'])->name('public.articles.index');

Route::get('/articles/{article}', [PublicArticleController::class, 'show'])->name('public.articles.show');

Route::get('/wiki', [PublicArticleController::class, 'index'])->name('wiki.index');
Route::get('/wiki/{article}', [PublicArticleController::class, 'show'])->name('wiki.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/articles/{article}/download-pdf', [ArticlePdfController::class, 'download'])
        ->name('articles.downloadPdf');

    Route::get('/articles/downloadAll', [ArticlePdfController::class, 'downloadAll'])
        ->middleware('auth')
        ->name('articles.downloadAll');

    Route::middleware(['auth', 'permission:manage articles'])->group(function () {
        Route::get('/articles/create', ArticleCrud::class)->name('articles.create');
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/articles', [ArticleApiController::class, 'store']);
    });

    Route::middleware('permission:manage articles')->group(function () {
        Route::get('/wiki/admin', fn () => view('wiki'))->name('admin.wiki');
    });

    Route::middleware('permission:manage categories')->group(function () {
        Route::get('/admin/categories', fn () => view('admin.categories'))->name('admin.categories');
    });
});

require __DIR__.'/auth.php';
