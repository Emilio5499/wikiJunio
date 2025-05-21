<?php

use App\Models\Article;
use App\Models\Comentario;
use App\Models\User;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

it('Logged user can comment in a post',function () {
    $user = User::factory()->create();
    $article = Article::factory()->create();

    Livewire::actingAs($user)
        ->test('comment-crud', ['articleId' => $article->id])
        ->set('content', 'Comentario generico')
        ->call('creaComentario')
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
        ->call('creaComentario')
        ->assertForbidden();
});

it('comment field cannot be empty', function () {
    $user = User::factory()->create();
    $article = Article::factory()->create();

    Livewire::actingAs($user)
        ->test('comment-crud', ['articleId' => $article->id])
        ->set('content', '')
        ->call('creaComentario')
        ->assertHasErrors(['content' => 'required']);
});

it('logged user can delete own comment', function () {
    $user = User::factory()->create();
    $article = Article::factory()->create();

    $comentario = Comentario::create([
        'user_id' => $user->id,
        'article_id' => $article->id,
        'content' => 'comentario ejemplo',
    ]);

    Livewire::actingAs($user)
        ->test('comment-crud', ['articleId' => $article->id])
        ->call('borraComentario', $comentario->id);

    $this->assertDatabaseMissing('comentarios', [
        'id' => $comentario->id,
    ]);
});

it('admin user can delete any comment', function () {
    Role::findOrCreate('admin');

    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $otroUsuario = User::factory()->create();
    $article = Article::factory()->create();

    $comentario = Comentario::create([
        'user_id' => $otroUsuario->id,
        'article_id' => $article->id,
        'content' => 'comentario ejemplo',
    ]);

    Livewire::actingAs($admin)
        ->test('comment-crud', ['articleId' => $article->id])
        ->call('borraComentario', $comentario->id);

    $this->assertDatabaseMissing('comentarios', [
        'id' => $comentario->id,
    ]);
});

it('logged user cannot delete other user comment', function () {
    $usuarioNormal = User::factory()->create();
    $otroUsuario = User::factory()->create();
    $article = Article::factory()->create();

    $comentario = Comentario::create([
        'user_id' => $otroUsuario->id,
        'article_id' => $article->id,
        'content' => 'Comentario usuario 1',
    ]);

    Livewire::actingAs($usuarioNormal)
        ->test('comment-crud', ['articleId' => $article->id])
        ->call('borraComentario', $comentario->id)
        ->assertForbidden();

    $this->assertDatabaseHas('comentarios', [
        'id' => $comentario->id,
        'content' => 'Comentario usuario 1',
    ]);
});
