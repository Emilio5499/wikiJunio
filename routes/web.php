<?php

use App\Http\Controllers\ProfileController;
use App\Models\Article;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/wiki', function () {
    return view('wiki');
})->middleware('auth');

Route::get('/', function () {
    $articles = Article::latest()->with('user')->get();
    return view('public', compact('articles'));
});

Route::get('/admin/categories', function () {
    return view('admin.categories');
})->middleware('auth');

require __DIR__.'/auth.php';
