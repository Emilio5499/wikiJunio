<?php

use App\Models\User;
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('permite a un usuario autenticado crear un post', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post('/api/articles', [
            'title' => 'Título',
            'content' => 'Contenido',
        ])
        ->assertStatus(201);

    expect(Article::where('title', 'Título')->exists())->toBeTrue();
});

it('no permite crear un post sin estar autenticado', function () {
    $this->postJson('/api/articles', [
        'title' => 'post no autorizado',
        'content' => 'post no permitido',
    ])->assertStatus(401);
});
