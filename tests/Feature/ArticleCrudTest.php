<?php

use App\Livewire\ArticleCrud;
use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Livewire\Livewire;

it('renders ArticleCrud component', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(ArticleCrud::class)
        ->assertStatus(200);
});

it('creates post with valid data', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();

    Livewire::actingAs($user)
        ->test(ArticleCrud::class)
        ->set('title', 'Post Livewire')
        ->set('content', 'Contenido')
        ->set('category_id', $category->id)
        ->call('create')
        ->assertHasNoErrors()
        ->assertSee('Post Livewire');

    expect(\App\Models\Article::where('title', 'Post Livewire')->exists())->toBeTrue();
});

it('validates required fields', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(ArticleCrud::class)
        ->set('title', '')
        ->set('content', '')
        ->set('category_id', '')
        ->call('create')
        ->assertHasErrors(['title', 'content', 'category_id']);
});

it('new post appears in list', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();

    Livewire::actingAs($user)
        ->test(ArticleCrud::class)
        ->set('title', 'Listado Test')
        ->set('content', 'Esto debería mostrarse')
        ->set('category_id', $category->id)
        ->call('create')
        ->assertSee('Listado Test');
});

it('can edit an existing post', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();

    $article = Article::factory()->create([
        'user_id' => $user->id,
        'title' => 'Original',
        'content' => 'original',
        'category_id' => $category->id,
    ]);

    Livewire::actingAs($user)
        ->test(\App\Livewire\ArticleCrud::class)
        ->call('edit', $article->id)
        ->set('title', 'Editado')
        ->set('content', 'nuevo')
        ->call('update');

    expect($article->fresh()->content)->toBe('nuevo');
});

it('validates fields when editing', function () {
    $user = User::factory()->create();
    $article = Article::factory()->create(['user_id' => $user->id]);

    Livewire::actingAs($user)
        ->test(\App\Livewire\ArticleCrud::class)
        ->call('edit', $article->id)
        ->set('title', '')
        ->set('content', '')
        ->call('update')
        ->assertHasErrors(['title', 'content']);
});

it('resetForm clears fields', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(\App\Livewire\ArticleCrud::class)
        ->set('title', 'Algo')
        ->set('content', 'Contenido')
        ->call('resetForm')
        ->assertSet('title', '')
        ->assertSet('content', '')
        ->assertSet('editing', false);
});

it('shows only articles of logged user', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    Article::factory()->create(['user_id' => $user1->id, 'title' => 'Propio']);
    Article::factory()->create(['user_id' => $user2->id, 'title' => 'de otro']);

    Livewire::actingAs($user1)
        ->test(\App\Livewire\ArticleCrud::class)
        ->assertSee('Propio')
        ->assertDontSee('de otro');
});

it('can assign collaborators on create', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();
    $collaborators = User::factory()->create();

    Livewire::actingAs($user)
        ->test(\App\Livewire\ArticleCrud::class)
        ->set('title', 'Post collab')
        ->set('content', 'Contenido')
        ->set('category_id', $category->id)
        ->set('collaborators', [$collaborators->id])
        ->call('create');

    $article = Article::where('title', 'Post collab')->first();
    expect($article->collaborators)->toHaveCount(1);
});

it('collaborator can see shared post', function () {
    $user = User::factory()->create();
    $collab = User::factory()->create();

    $article = Article::factory()->create(['user_id' => $user->id]);
    $article->collaborators()->attach($collab->id);

    Livewire::actingAs($collab)
        ->test(\App\Livewire\ArticleCrud::class)
        ->assertSee($article->title);
});

it('can delete own post', function () {
    $user = User::factory()->create();
    $article = Article::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(\App\Livewire\ArticleCrud::class)
        ->call('deleteArticle', $article->id);

    expect(Article::find($article->id))->toBeNull();
});

it('cancel edit resets the form', function () {
    $user = User::factory()->create();
    $article = Article::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(\App\Livewire\ArticleCrud::class)
        ->call('edit', $article->id)
        ->call('resetForm')
        ->assertSet('editing', false)
        ->assertSet('title', '')
        ->assertSet('content', '');
});

it('admin can edit and delete any post', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $author = User::factory()->create();
    $article = Article::factory()->for($author)->create([
        'title' => 'Admin puede',
    ]);

    Livewire::actingAs($admin)
        ->test(\App\Livewire\ArticleCrud::class)
        ->call('edit', $article->id)
        ->set('title', 'Actualizado por admin')
        ->call('update');

    expect($article->fresh()->title)->toBe('Actualizado por admin');

    Livewire::actingAs($admin)
        ->test(\App\Livewire\ArticleCrud::class)
        ->call('deleteArticle', $article->id);

    expect(Article::find($article->id))->toBeNull();
});
