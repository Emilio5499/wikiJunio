<?php

namespace App\Livewire;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ArticleCrud extends Component
{
    protected $listeners = ['edit', 'deleteArticle'];

    public $title, $content, $category_id, $tags = [], $usage_types = [];
    public $editing = false;
    public $article_id;

    public $categories;
    public $availableTags;
    public $articles;
    public function mount()
    {
        $this->categories = Category::all();
        $this->availableTags = Tag::all();
        $this->articles = Auth::user()?->articles()->with('tags')->latest()->get() ?? collect();
    }


    public function create()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $article = Auth::user()->articles()->create([
            'title' => $this->title,
            'content' => $this->content,
            'category_id' => $this->category_id,
        ]);

        foreach ($this->tags as $tagId) {
            $article->tags()->attach($tagId, [
                'usage_type' => $this->usage_types[$tagId] ?? 'post nuevo',
            ]);
        }

        session()->flash('success', 'Artículo creado correctamente.');
        $this->resetForm();
        $this->articles = Auth::user()->articles()->with('tags')->latest()->get();
    }

    public function edit($id)
    {
        $article = Article::findOrFail($id);

        if (auth()->id() !== $article->user_id && !auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $this->editing = true;
        $this->article_id = $article->id;
        $this->title = $article->title;
        $this->content = $article->content;
        $this->category_id = $article->category_id;
        $this->tags = $article->tags->pluck('id')->toArray();
        $this->usage_types = $article->tags->pluck('pivot.usage_type', 'id')->toArray();
    }

    public function update()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $article = Article::findOrFail($this->article_id);

        if (auth()->id() !== $article->user_id && !auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $article->update([
            'title' => $this->title,
            'content' => $this->content,
            'category_id' => $this->category_id,
        ]);

        $syncData = [];
        foreach ($this->tags as $tagId) {
            $syncData[$tagId] = ['usage_type' => $this->usage_types[$tagId] ?? 'post nuevo'];
        }

        $article->tags()->sync($syncData);

        session()->flash('success', 'Post actualizado.');
        $this->resetForm();
        $this->loadArticles();
    }

    public function deleteArticle($id)
    {
        $article = Article::findOrFail($id);

        if (auth()->id() !== $article->user_id && !auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $article->delete();
        session()->flash('success', 'Post borrado');
        $this->articles = Auth::user()->articles()->with('tags')->latest()->get();
    }

    public function loadArticles()
    {
        $this->articles = auth()->user()->hasRole('admin')
            ? Article::with('user', 'tags')->latest()->get()
            : Auth::user()->articles()->with('tags')->latest()->get();
    }


    public function resetForm()
    {
        $this->editing = false;
        $this->title = '';
        $this->content = '';
        $this->category_id = '';
        $this->tags = [];
        $this->usage_types = [];
        $this->article_id = null;
    }

    public function render()
    {
        return view('livewire.article-crud');
    }
}

