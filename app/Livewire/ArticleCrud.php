<?php

namespace App\Livewire;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ArticleCrud extends Component
{
    public $title = '';
    public $content = '';
    public $category_id = '';
    public $tags = [];
    public $usage_types = [];

    public $editing = false;
    public $article_id;

    public $articles;
    public $categories;
    public $availableTags;

    public function mount()
    {
        $this->categories = Category::all();
        $this->availableTags = Tag::all();
        $this->articles = Auth::user()->articles()->latest()->get();
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
        $article = Auth::user()->articles()->with('tags')->findOrFail($id);

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

        $article = Auth::user()->articles()->findOrFail($this->article_id);

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
        $this->articles = Auth::user()->articles()->with('tags')->latest()->get();
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

