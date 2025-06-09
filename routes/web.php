<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    Api\ArticleApiController,
    ArticlePdfController,
    ProfileController,
    PublicArticleController
};
use App\Http\Middleware\SetLocale;

Route::middleware([SetLocale::class])->group(function () {

    Route::get('lang/{locale}', function ($locale) {
        session(['locale' => $locale]);
        return redirect()->back();
    })->name('lang.switch');

    Route::middleware(['auth'])->group(function () {
        Route::get('/articles/{article}/edit', [PublicArticleController::class, 'edit'])->name('articles.edit');
        Route::put('/articles/{article}', [PublicArticleController::class, 'update'])->name('articles.update');
    });

    Route::get('/articles/create', function () {
        return view('articles.create');
    })->name('articles.create');

    Route::get('/articles/{article}/download-pdf', [ArticlePdfController::class, 'download'])
        ->middleware('auth')
        ->name('articles.downloadPdf');

    Route::get('/articles/downloadAll', [ArticlePdfController::class, 'downloadAll'])
        ->middleware('auth')
        ->name('articles.downloadAll');

    Route::get('/', [PublicArticleController::class, 'index'])->name('public.articles.index');
    Route::get('/articles/{article}', [PublicArticleController::class, 'show'])->name('public.articles.show');

    Route::get('/wiki', [PublicArticleController::class, 'index'])->name('wiki.index');
    Route::get('/wiki/{article}', [PublicArticleController::class, 'show'])->name('wiki.show');

    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::post('/articles', [ArticleApiController::class, 'store'])->middleware('auth:sanctum');
    });

    Route::middleware(['auth', 'permission:manage categories'])->get('/admin/categories', fn () => view('admin.categories'))->name('admin.categories');
    Route::middleware(['auth', 'permission:manage tags'])->get('/admin/tags', fn () => view('admin.tags'))->name('admin.tags');
});

require __DIR__.'/auth.php';

