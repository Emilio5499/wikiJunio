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

uses(TestCase::class, Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
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


