<?php

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Carbon\Carbon;

it('shows public articles', function () {
    $article = Article::factory()->create(['title' => 'post visible']);

    $response = $this->get('/wiki');

    $response->assertStatus(200);
    $response->assertSee('post visible');
});

it('shows author name and formatted date', function () {
    $article = Article::factory()->create([
        'title' => 'Post',
        'created_at' => Carbon::parse('2023-01-01'),
    ]);

    $response = $this->get('/wiki');

    $response->assertSee('Post');
    $response->assertSee($article->user->name);
    $response->assertSee('01/01/2023');
});

it('can view a single article', function () {
    $article = Article::factory()->create(['title' => 'Post Individual']);

    $response = $this->get(route('wiki.show', $article));

    $response->assertStatus(200);
    $response->assertSee('Post Individual');
    $response->assertSee($article->content);
});

it('shows assigned tags in article view', function () {
    $article = Article::factory()->create();
    $tag = Tag::factory()->create(['name' => 'TagTest']);

    $article->tags()->attach($tag->id, ['usage_type' => 'spoiler']);

    $response = $this->get(route('wiki.show', $article));

    $response->assertStatus(200);
    $response->assertSee('TagTest');
});

it('shows a message when no articles are present', function () {
    $response = $this->get('/wiki');

    $response->assertStatus(200);
    $response->assertSee('posts.no_posts');
});

it('can filter articles by category', function () {
    $category1 = Category::factory()->create();
    $category2 = Category::factory()->create();

    $article1 = Article::factory()->create(['title' => 'Post en Cat 1', 'category_id' => $category1->id]);
    $article2 = Article::factory()->create(['title' => 'Post en Cat 2', 'category_id' => $category2->id]);

    $response = $this->get('/wiki?categoria=' . $category1->id);

    $response->assertStatus(200);
    $response->assertSee('Post en Cat 1');
    $response->assertDontSee('Post en Cat 2');
});

it('can search articles by title', function () {
    Article::factory()->create(['title' => 'Artículo especial']);
    Article::factory()->create(['title' => 'Otro artículo']);

    $response = $this->get('/wiki?search=especial');

    $response->assertStatus(200);
    $response->assertSee('Artículo especial');
    $response->assertDontSee('Otro artículo');
});
