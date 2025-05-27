<?php

use App\Models\Article;
use App\Models\Category;
use App\Models\Comentario;
use Illuminate\Support\Carbon;


it('returns posts ordered by date', function () {
    Article::factory()->create(['created_at' => now()->subDays(1)]);
    Article::factory()->create(['created_at' => now()]);

    $articles = Article::publicadosRecientes()->get();

    expect($articles->first()->created_at->isToday())->toBeTrue();
});

it('filter by category', function () {
    $categoria1 = Category::factory()->create();
    $categoria2 = Category::factory()->create();

    Article::factory()->create(['category_id' => $categoria1->id]);
    Article::factory()->create(['category_id' => $categoria2->id]);

    $filtrados = Article::porCategoria($categoria1->id)->get();

    expect($filtrados)->toHaveCount(1);
    expect($filtrados->first()->category_id)->toBe($categoria1->id);
});
