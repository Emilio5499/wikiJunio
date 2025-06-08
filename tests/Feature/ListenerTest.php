<?php

use App\Events\ArticuloCreado;
use App\Events\ColaboradorNuevo;
use App\Listeners\LogArticuloCreado;
use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

it('sends mail to collaborator when ColaboradorNuevo activates', function () {
    Mail::fake();

    $user = User::factory()->create();
    $article = Article::factory()->create();

    $listener = new \App\Listeners\mailAColaboradorListener();
    $event = new ColaboradorNuevo($article, $user);

    $listener->handle($event);

    Mail::assertSent(\App\Mail\mailAColaborador::class, function ($mail) use ($user) {
        return $mail->hasTo($user->email);
    });
});

it('writes in log when a new post is created', function () {
    Log::shouldReceive('info')
        ->once()
        ->withArgs(fn ($message) => str_contains($message, 'Artículo creado:'));

    $article = Article::factory()->create();
    $listener = new LogArticuloCreado();
    $event = new ArticuloCreado($article);

    $listener->handle($event);
});

