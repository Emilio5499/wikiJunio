<?php

use App\Livewire\CategoryCrud;
use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

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

it('logged user can update his category', function () {

    $user = User::factory()->create();

    $this->actingAs($user, 'sanctum');

    $category = Category::factory()->create([
        'name' => 'titulo1',
    ]);

    Article::factory()->create([
        'user_id' => $user->id,
        'category_id' => $category->id,
    ]);

    $this->putJson("/api/categories/{$category->id}", [
        'name' => 'titulo2',
    ])
        ->assertOk()
        ->assertJsonFragment([
            'name' => 'titulo2',
        ]);
});


