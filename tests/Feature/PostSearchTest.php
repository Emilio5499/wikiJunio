<?php

use App\Models\Article;
use App\Models\Category;
use App\Models\Comentario;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can search articles by title or content', function () {
    Article::factory()->create(['title' => 'unico']);
    Article::factory()->create(['title' => 'Otro post']);

    $response = $this->get('/wiki?search=unico');

    $response->assertStatus(200);
    $response->assertSee('unico');
    $response->assertDontSee('Otro post');
});

it('can filter articles by category', function () {
    $catLaravel = Category::factory()->create(['name' => 'Laravel']);
    $catQueso = Category::factory()->create(['name' => 'queso']);

    Article::factory()->create(['title' => 'Laravel Post', 'category_id' => $catLaravel->id]);
    Article::factory()->create(['title' => 'Queso Post', 'category_id' => $catQueso->id]);

    $response = $this->get("/wiki?categoria={$catLaravel->id}");

    $response->assertStatus(200);
    $response->assertSee('Laravel Post');
    $response->assertDontSee('Queso Post');
});

it('search ignores case', function () {
    Article::factory()->create(['title' => 'Laravel con wiki']);
    Article::factory()->create(['title' => 'melocoton']);

    $response = $this->get('/wiki?search=LARAVEL');

    $response->assertStatus(200);
    $response->assertSee('Laravel con wiki');
    $response->assertDontSee('melocoton');
});

it('finds articles by content', function () {
    Article::factory()->create([
        'title' => 'Titulo random',
        'content' => 'Tests de laravel',
    ]);

    Article::factory()->create([
        'title' => 'otro random',
        'content' => 'tengo hambre',
    ]);

    $response = $this->get('/wiki?search=tests');

    $response->assertStatus(200);
    $response->assertSee('Titulo random');
    $response->assertDontSee('otro random');
});
