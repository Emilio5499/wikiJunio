<?php

use App\Models\Article;
use App\Models\Category;
use App\Models\User;

test('example', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

it('lists categories with articles count', function () {
    Category::factory()
        ->has(Article::factory()->count(2))
        ->create();

    $this->getJson('/api/categories')
        ->assertOk()
        ->assertJsonStructure([
            '*' => ['id', 'name', 'articles_count']
        ]);
});

it('returns a list of categories', function () {
    Category::factory()->count(2)->create();

    $this->getJson('/api/categories')
        ->assertOk()
        ->assertJsonCount(2);
});

it('return category + articles for auth user', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();

    Article::factory()->create([
        'category_id' => $category->id,
        'user_id' => $user->id,
    ]);

    $this->actingAs($user)
        ->getJson("/api/categories/{$category->id}")
        ->assertOk()
        ->assertJson([
            'id' => $category->id,
        ])
        ->assertJsonStructure([
            'id',
            'articles',
        ]);
});

it('logged user can update a category that has his articles', function () {

    $user = User::factory()->create();

    $this->actingAs($user, 'sanctum');

    $category = Category::factory()->create([
        'name' => 'Nombre viejo',
    ]);

    Article::factory()->create([
        'user_id' => $user->id,
        'category_id' => $category->id,
    ]);

    $this->putJson("/api/categories/{$category->id}", [
        'name' => 'Nombre nuevo',
    ])
        ->assertOk()
        ->assertJsonFragment([
            'name' => 'Nombre nuevo',
        ]);
});
