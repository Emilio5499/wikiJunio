<?php

use App\Models\Article;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;

it('logged user can download PDF', function () {
    Storage::fake('local');
    $user = \App\Models\User::factory()->create();
    $article = \App\Models\Article::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user);

    $response = $this->get(route('articles.downloadPdf', $article));

    $response->assertStatus(200);
    $response->assertHeader('content-type', 'application/pdf');
});

it('guest cannot download pdf', function () {
    $article = \App\Models\Article::factory()->create();

    $this->get(route('articles.downloadPdf', $article))
        ->assertRedirect('/login');
});

it('logged user can download all post in one PDF', function () {
    $user = \App\Models\User::factory()->create();
    \App\Models\Article::factory()->count(3)->create();

    $this->actingAs($user);

    $response = $this->get(route('articles.downloadAll'));

    $response->assertStatus(200);
    $response->assertHeader('content-type', 'application/pdf');
});

it('pdf export has titles', function () {
    $user = \App\Models\User::factory()->create();
    $article = \App\Models\Article::factory()->create(['title' => 'Titulo PDF']);

    $this->actingAs($user);

    $response = $this->get(route('articles.downloadAll'));

    $response->assertStatus(200);
});

it('guest cannot download all PDF', function () {
    $this->get(route('articles.downloadAll'))
        ->assertRedirectContains('/login');
});
