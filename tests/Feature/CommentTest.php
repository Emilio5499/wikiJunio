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
        ->call('deleteComment', $comentario->id);

    expect(Comentario::find($comentario->id))->toBeNull();
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
        ->call('deleteComment', $comentario->id);

    expect(Comentario::find($comentario->id))->toBeNull();
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
        ->call('deleteComment', $comentario->id)
        ->assertForbidden();

    expect(Comentario::find($comentario->id))->not()->toBeNull();
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
        ->call('startEdit', $comentario->id)
        ->set('editContent', 'Editado')
        ->call('updateComment');

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
        ->call('startEdit', $comentario->id, 'Editado fallo')
        ->assertForbidden();

    $this->assertDatabaseHas('comentarios', [
        'id' => $comentario->id,
        'content' => 'Original',
    ]);
});

it('shows comments latest first', function () {
    $user = User::factory()->create();
    $article = Article::factory()->create();

    Comentario::factory()->create([
        'article_id' => $article->id,
        'user_id' => $user->id,
        'content' => 'Comentario viejo',
        'created_at' => now()->subDay(),
    ]);

    Comentario::factory()->create([
        'article_id' => $article->id,
        'user_id' => $user->id,
        'content' => 'Comentario nuevo',
        'created_at' => now(),
    ]);

    $component = Livewire::actingAs($user)
        ->test('comment-crud', ['articleId' => $article->id]);

    $component->assertSeeInOrder([
        'Comentario nuevo',
        'Comentario viejo',
    ]);
});

it('new comment visible after creation', function () {
    $user = User::factory()->create();
    $article = Article::factory()->create();

    $component = Livewire::actingAs($user)
        ->test('comment-crud', ['articleId' => $article->id])
        ->set('content', 'Comentario nuevo')
        ->call('creaComentario');

    $component->assertSee('Comentario nuevo');
});

it('content field resets after comment', function () {
    $user = User::factory()->create();
    $article = Article::factory()->create();

    Livewire::actingAs($user)
        ->test('comment-crud', ['articleId' => $article->id])
        ->set('content', 'comentario')
        ->call('creaComentario')
        ->assertSet('content', '');
});

it('guest cannot see comment form', function () {
    $article = \App\Models\Article::factory()->create();

    $this->get(route('wiki.show', $article))
        ->assertDontSee('comentario')
        ->assertDontSee('Enviar');
});

it('user can see own comment after posting', function () {
    $user = \App\Models\User::factory()->create();
    $article = \App\Models\Article::factory()->create();

    \App\Models\Comentario::factory()->create([
        'article_id' => $article->id,
        'user_id' => $user->id,
        'content' => 'Mi propio comentario',
    ]);

    $this->actingAs($user)
        ->get(route('wiki.show', $article))
        ->assertSee('Mi propio comentario');
});

it('comment is saved in the database', function () {
    $user = \App\Models\User::factory()->create();
    $article = \App\Models\Article::factory()->create();

    Livewire::actingAs($user)
        ->test('comment-crud', ['articleId' => $article->id])
        ->set('content', 'en bd')
        ->call('creaComentario');

    $this->assertDatabaseHas('comentarios', [
        'content' => 'en bd',
        'user_id' => $user->id,
        'article_id' => $article->id,
    ]);
});
