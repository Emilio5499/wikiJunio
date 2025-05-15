<?php

use App\Models\Article;
use App\Models\User;
use function Pest\Laravel\{actingAs, getJson, putJson, deleteJson};

it('logged user can list posts', function () {
    $user = User::factory()->create();
    $articles = Article::factory()->count(3)->for($user)->create();

    actingAs($user);

    $response = getJson('/api/articles');

    $response->assertStatus(200);
    $response->assertJsonCount(3);
    $response->assertJsonFragment(['title' => $articles[0]->title]);
});

it('logged user can see own posts', function () {
    $user = User::factory()->create();
    $article = Article::factory()->for($user)->create();

    actingAs($user);

    $response = getJson("/api/articles/{$article->id}");

    $response->assertStatus(200);
    $response->assertJsonFragment([
        'id' => $article->id,
        'title' => $article->title,
    ]);
});

it('logged user can update own posts', function () {
    $user = User::factory()->create();
    $article = Article::factory()->for($user)->create();

    actingAs($user);

    $newData = [
        'title' => 'Titulo actualizado',
        'content' => 'Contenido actualizado.',
    ];

    $response = putJson("/api/articles/{$article->id}", $newData);

    $response->assertStatus(200);
    $response->assertJsonFragment(['title' => 'Titulo actualizado']);

    expect($article->fresh()->title)->toBe('Titulo actualizado');
});

it('logged user can delete own posts', function () {
    $user = User::factory()->create();
    $article = Article::factory()->for($user)->create();

    actingAs($user);

    $response = deleteJson("/api/articles/{$article->id}");

    $response->assertStatus(200);
    $response->assertJsonFragment(['message' => 'post borrado']);

    expect(Article::find($article->id))->toBeNull();
});

it('user cannot edit other user post', function () {
    $user = User::factory()->create();
    $otro = User::factory()->create();

    $article = Article::factory()->for($otro)->create();

    actingAs($user);

    putJson("/api/articles/{$article->id}", [
        'title' => 'Editar',
        'content' => 'contenido',
    ])->assertStatus(404);
});

it('user cannot delete other user post', function () {
    $user = User::factory()->create();
    $otro = User::factory()->create();

    $article = Article::factory()->for($otro)->create();

    actingAs($user);

    deleteJson("/api/articles/{$article->id}")
        ->assertStatus(404);
});
