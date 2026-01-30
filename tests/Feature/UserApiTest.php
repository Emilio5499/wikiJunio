<?php

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

test('example', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

it('returns a list of users', function () {
    User::factory()->count(2)->create();

    $this->getJson('/api/user')
        ->assertOk()
        ->assertJsonCount(2);
});
