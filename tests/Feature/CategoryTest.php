<?php

use App\Models\Category;
use function Pest\Laravel\getJson;

it('returns category list', function () {
    $user = \App\Models\User::factory()->create();
    $this->actingAs($user, 'sanctum');

    Category::factory()->count(3)->create();

    $response = getJson('/api/categories');

    $response->assertStatus(200);
    $response->assertJsonCount(3);
});


it('category HAS name and post counter', function () {
    $user = \App\Models\User::factory()->create();
    $this->actingAs($user, 'sanctum');

    $category = Category::factory()->create(['name' => 'Ejemplo']);

    $response = getJson('/api/categories');

    $response->assertJsonFragment([
        'name' => 'Ejemplo',
        'articles_count' => 0,
    ]);
});
