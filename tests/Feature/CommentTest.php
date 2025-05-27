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
        ->set('content', 'Comentario genérico')
        ->call('creaComentario')
        ->assertSet('content', '');

    $comentario = Comentario::factory()->create([
        'user_id' => $user->id,
        'article_id' => $article->id,
        'content' => 'Original',
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

    $comentario = Comentario::factory()->create([
        'user_id' => $user->id,
        'article_id' => $article->id,
        'content' => 'Original',
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

    $comentario = Comentario::factory()->create([
        'user_id' => $otroUsuario->id,
        'article_id' => $article->id,
        'content' => 'Original',
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

    $comentario = Comentario::factory()->create([
        'user_id' => $otroUsuario->id,
        'article_id' => $article->id,
        'content' => 'Original',
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

it('logged user can edit own comment', function () {
    $user = User::factory()->create();
    $article = Article::factory()->create();

    $comentario = Comentario::create([
        'user_id' => $user->id,
        'article_id' => $article->id,
        'content' => 'Original',
    ]);

    Livewire::actingAs($user)
        ->test('comment-crud', ['articleId' => $article->id])
        ->call('editaComentario', $comentario->id, 'Editado');

    $this->assertDatabaseHas('comentarios', [
        'id' => $comentario->id,
        'content' => 'Editado',
    ]);
});

it('logged user cannot edit other user comment', function () {
    $usuario = User::factory()->create();
    $otroUsuario = User::factory()->create();
    $article = Article::factory()->create();

    $comentario = Comentario::factory()->create([
        'user_id' => $otroUsuario->id,
        'article_id' => $article->id,
        'content' => 'Original',
    ]);

    Livewire::actingAs($usuario)
        ->test('comment-crud', ['articleId' => $article->id])
        ->call('editaComentario', $comentario->id, 'Editado fallo')
        ->assertForbidden();

    $this->assertDatabaseHas('comentarios', [
        'id' => $comentario->id,
        'content' => 'Original',
    ]);
});

it('admin cannot edit other user comment', function () {
    Role::findOrCreate('admin');
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $autor = User::factory()->create();
    $article = Article::factory()->create();

    $comentario = Comentario::factory()->create([
        'user_id' => $autor->id,
        'article_id' => $article->id,
        'content' => 'Original',
    ]);

    Livewire::actingAs($admin)
        ->test('comment-crud', ['articleId' => $article->id])
        ->call('editaComentario', $comentario->id, 'Editado admin falla')
        ->assertForbidden();

    $this->assertDatabaseHas('comentarios', [
        'id' => $comentario->id,
        'content' => 'Original',
    ]);
});
