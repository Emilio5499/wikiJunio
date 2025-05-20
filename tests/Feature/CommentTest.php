<?php

use App\Models\Article;
use App\Models\User;
use Livewire\Livewire;

it('Logged user can comment in a post',function () {
    $user = User::factory()->create();
    $article = Article::factory()->create();

    Livewire::actingAs($user)
        ->test('comment-crud', ['articleId' => $article->id])
        ->set('content', 'Comentario generico')
        ->call('addComment')
        ->assertSet('content', '');

    $this->assertDatabaseHas('comentarios', [
        'content' => 'Comentario generico',
        'user_id' => $user->id,
        'article_id' => $article->id,
    ]);
});

it('guest cannot comment on a post', function () {
    $article = Article::factory()->create();

    Livewire::test('comment-crud', ['articleId' => $article->id])
        ->set('content', 'Comentario falla')
        ->call('addComment')
        ->assertForbidden();
});

it('comment field cannot be empty', function () {
    $user = User::factory()->create();
    $article = Article::factory()->create();

    Livewire::actingAs($user)
        ->test('comment-crud', ['articleId' => $article->id])
        ->set('content', '')
        ->call('addComment')
        ->assertHasErrors(['content' => 'required']);
});
