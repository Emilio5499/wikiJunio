<?php

use App\Mail\ColaboradorNotificacion;
use App\Mail\NewArticleMail;
use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

it('mails an admin when a post is created', function () {
    Mail::fake();

    $admin = User::factory()->create(['email' => 'admin@example.com']);
    $article = Article::factory()->create();

    Mail::to($admin->email)->send(new NewArticleMail($article));

    Mail::assertSent(NewArticleMail::class, function ($mail) use ($article) {
        return $mail->article->id === $article->id;
    });
});

it('mails a collaborator when assigned', function () {
    Mail::fake();

    $user = User::factory()->create();
    $article = Article::factory()->create();

    Mail::to($user->email)->send(new \App\Mail\ColaboradorNotificacion($article));

    Mail::assertSent(ColaboradorNotificacion::class, function ($mail) use ($user) {
        return $mail->hasTo($user->email);
    });
});

it('new article mail has correct title', function () {
    $article = Article::factory()->create();

    $mail = new \App\Mail\NewArticleMail($article);

    expect($mail->build()->subject)->toBe('Nuevo post publicado');
});

it('new article mail uses correct view', function () {
    $article = Article::factory()->create();

    $mail = new \App\Mail\NewArticleMail($article);
    $built = $mail->build();

    expect($built->view)->toBe('emails.new-article');
});

it('new article mail envelope and content are correct', function () {
    $article = Article::factory()->create();
    $mail = new \App\Mail\NewArticleMail($article);

    expect($mail->envelope()->subject)->toBe('New Article Mail');
    expect($mail->content()->view)->toBe('view.name');
});

