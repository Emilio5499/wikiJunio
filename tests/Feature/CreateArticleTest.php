<?php

use App\Models\Category;
use App\Models\User;
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use function Pest\Laravel\{actingAs, postJson};

uses(RefreshDatabase::class);

it('logged user can create posts', function () {
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

it('guest user cannot create posts', function () {
    $this->postJson('/api/articles', [
        'title' => 'post no autorizado',
        'content' => 'post no permitido',
    ])->assertStatus(401);
});

it('post requires valid title, content and category', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();

    actingAs($user, 'sanctum');

    $response = postJson('/api/articles', [
        'title' => 'post de prueba',
        'content' => 'contenido de prueba',
        'category_id' => $category->id,
    ]);

    $response->assertStatus(201)
        ->assertJsonFragment([
            'title' => 'post de prueba',
            'content' => 'contenido de prueba',
            'category_id' => $category->id,
        ]);

    $this->assertDatabaseHas('articles', [
        'title' => 'post de prueba',
        'category_id' => $category->id,
        'user_id' => $user->id,
    ]);
});

it('post requires existing category', function () {
    $user = User::factory()->create();

    actingAs($user, 'sanctum');

    $response = postJson('/api/articles', [
        'title' => 'Post de prueba',
        'content' => 'contenido generico',
        'category_id' => 99999,
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['category_id']);
});
