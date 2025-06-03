<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    Api\ArticleApiController,
    ArticlePdfController,
    ProfileController,
    PublicArticleController
};
use App\Livewire\ArticleCrud;

Route::get('/', [PublicArticleController::class, 'index'])->name('public.articles.index');

Route::get('/articles/{article}', [PublicArticleController::class, 'show'])->name('public.articles.show');

Route::get('/wiki', [PublicArticleController::class, 'index'])->name('wiki.index');
Route::get('/wiki/{article}', [PublicArticleController::class, 'show'])->name('wiki.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/articles/{article}/download-pdf', [ArticlePdfController::class, 'download'])->name('articles.downloadPdf');
    Route::get('/articles/downloadAll', [ArticlePdfController::class, 'downloadAll'])->name('articles.downloadAll');

    Route::post('/articles', [ArticleApiController::class, 'store'])->middleware('auth:sanctum');
});

    Route::middleware(['auth'])->get('/articles/create', function () {
        return view('articles.create');
    })->name('articles.create');



Route::middleware(['auth', 'permission:manage categories'])->get('/admin/categories', fn () => view('admin.categories'))->name('admin.categories');

require __DIR__.'/auth.php';
