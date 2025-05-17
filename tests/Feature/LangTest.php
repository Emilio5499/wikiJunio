<?php

use App\Models\User;
use function Pest\Laravel\{postJson, actingAs};

it('validation messages are in spanish', function () {
    $user = User::factory()->create();

    actingAs($user, 'sanctum');

    app()->setLocale('es');

    $response = postJson('/api/articles', []);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['title', 'content']);
});

it('validation messages are in english if lang is en', function () {
    $user = User::factory()->create();

    actingAs($user, 'sanctum');

    app()->setLocale('en');

    $response = postJson('/api/articles', []);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['title', 'content']);
});
