<?php

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use function Pest\Laravel\{actingAs, getJson, postJson, putJson, deleteJson};

it('logged user can create posts', function () {
    $user = User::factory()->create();

    actingAs($user, 'sanctum');

    $data = [
        'title' => 'Nuevo post',
        'content' => 'Texto del post',
        'category_id' => \App\Models\Category::factory()->create()->id,
    ];

    $response = postJson('/api/articles', $data);

    $response->assertStatus(201);
    $response->assertJsonFragment(['title' => 'Nuevo post']);
});

it('logged user can list posts', function () {
    $user = User::factory()->create();
    $articles = Article::factory()->count(3)->for($user)->create();

    actingAs($user, 'sanctum');

    $response = getJson('/api/articles');

    $response->assertStatus(200);
    $response->assertJsonCount(3);
    $response->assertJsonFragment(['title' => $articles[0]->title]);
});

it('logged user can see own posts', function () {
    $user = User::factory()->create();
    $article = Article::factory()->for($user)->create();

    actingAs($user, 'sanctum');

    $response = getJson("/api/articles/{$article->id}");

    $response->assertStatus(200);
    $response->assertJsonFragment([
        'id' => $article->id,
        'title' => $article->title,
    ]);
});

it('logged user can update post', function () {

    $user = User::factory()->create();

    actingAs($user, 'sanctum');

    $category = Category::factory()->create();

    $article = Article::factory()->create([
        'user_id' => $user->id,
        'title' => 'Titulo1',
        'content' => 'Contenido1',
    ]);

    $data = [
        'title' => 'Titulo2',
        'content' => 'Contenido2',
        'category_id' => $category->id,
    ];

    $response = putJson("/api/articles/{$article->id}", $data);

    $response->assertStatus(200);

    $response->assertJsonFragment([
        'message' => 'actualizado',
        'title' => 'Titulo2',
    ]);

    $this->assertDatabaseHas('articles', [
        'id' => $article->id,
        'title' => 'Titulo2',
        'content' => 'Contenido2',
        'category_id' => $category->id,
    ]);
});
