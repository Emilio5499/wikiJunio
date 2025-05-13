<?php

use App\Models\User;
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

it('permite a un usuario autenticado crear un artículo', function () {
    Permission::create(['name' => 'manage posts']);

    $user = User::factory()->create();
    $user->givePermissionTo('manage posts');

    $this->actingAs($user, 'sanctum')
        ->postJson('/api/articles', [
            'title' => 'Título de prueba',
            'content' => 'Contenido de prueba',
        ])
        ->assertStatus(201)
        ->assertJsonFragment([
            'title' => 'Título de prueba',
        ]);

    expect(Article::where('title', 'Título de prueba')->exists())->toBeTrue();
});

it('no permite crear un post sin estar autenticado', function () {
    $this->postJson('/api/articles', [
        'title' => 'post no autorizado',
        'content' => 'post no permitido',
    ])->assertStatus(401);
});
