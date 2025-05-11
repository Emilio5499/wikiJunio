<?php

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('filtra posts publicados con el scope ', function () {
    Article::factory()->create(['published_at' => now()]);
    Article::factory()->create(['published_at' => null]);

    $publicados = Article::published()->get();

    expect($publicados)->toHaveCount(1);
});
