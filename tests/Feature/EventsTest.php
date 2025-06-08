<?php

use App\Events\ArticuloCreado;
use App\Events\ColaboradorNuevo;
use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Facades\Event;

it('throws event when a post is created', function () {
    Event::fake();

    $user = User::factory()->create();

    $article = Article::factory()->create(['user_id' => $user->id]);

    event(new ArticuloCreado($article));

    Event::assertDispatched(ArticuloCreado::class, function ($e) use ($article) {
        return $e->article->id === $article->id;
    });
});

it('throws Colaborador nuevo when a new collaborator is assigned', function () {
    Event::fake();

    $article = Article::factory()->create();
    $user = User::factory()->create();

    event(new ColaboradorNuevo($article, $user));

    Event::assertDispatched(ColaboradorNuevo::class, function ($e) use ($article, $user) {
        return $e->article->id === $article->id &&
            $e->user->id === $user->id;
    });
});
