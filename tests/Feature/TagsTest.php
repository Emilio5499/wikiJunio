<?php

use Livewire\Livewire;
use App\Models\{User, Tag, Article};

it('can add tags to a post', function () {
    $user = User::factory()->create();
    $article = Article::factory()->create(['user_id' => $user->id]);

    $tag = Tag::factory()->create();

    $article->tags()->attach($tag->id, ['usage_type' => 'spoiler']);

    $this->assertDatabaseHas('article_tag', [
        'article_id' => $article->id,
        'tag_id' => $tag->id,
        'usage_type' => 'spoiler',
    ]);
});

it('cannot duplicate tags for the same article', function () {
    $article = Article::factory()->create();
    $tag = Tag::factory()->create();

    $article->tags()->attach($tag->id, ['usage_type' => 'debate']);

    try {
        $article->tags()->attach($tag->id, ['usage_type' => 'spoiler']);
        $this->fail('Se puede duplicar tag en la tabla pivote');
    } catch (\Illuminate\Database\QueryException $e) {
        $this->assertTrue(true);
    }
});

it('shows post tags public page', function () {
    $article = Article::factory()->create();
    $tag = Tag::factory()->create(['name' => 'Laravel']);

    $article->tags()->attach($tag->id, ['usage_type' => 'post nuevo']);

    $this->get(route('wiki.show', $article))
        ->assertSee('Laravel');
});

it('can create post with tags from Livewire', function () {
    $user = User::factory()->create();
    $category = \App\Models\Category::factory()->create();
    $tag = Tag::factory()->create();

    Livewire::actingAs($user)
        ->test('article-crud')
        ->set('title', 'Post con tag')
        ->set('content', 'Contenido del post')
        ->set('category_id', $category->id)
        ->set('tags', [$tag->id])
        ->set("usage_types.{$tag->id}", 'spoiler')
        ->call('create')
        ->assertHasNoErrors();

    $article = Article::where('title', 'Post con tag')->first();

    expect($article->tags()->where('tag_id', $tag->id)->exists())->toBeTrue();
    expect($article->tags()->first()->pivot->usage_type)->toBe('spoiler');
});

