<?php

namespace App\Livewire;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class ArticleCrud extends Component
{
    public $title, $content, $category_id, $tags = [], $usage_types = [];
    public $collaborators = [];
    public $editing = false;
    public $article_id;
    public $categories;
    public $availableTags;
    public $articles;
    public $allUsers;

    protected $listeners = ['edit', 'deleteArticle'];

    public function mount()
    {
        $this->categories = Category::all();
        $this->availableTags = Tag::all();
        $this->allUsers = User::all();
        $this->loadArticles();
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

        $article->collaborators()->sync($this->collaborators);

        foreach ($this->collaborators as $userId) {
            $user = User::find($userId);
            Mail::raw("Has sido añadido como colaborador en el post: {$article->title}", function ($message) use ($user) {
                $message->to($user->email)->subject('Asignado como colaborador');
            });
        }

        Mail::raw("Se ha creado un post: {$article->title}", function ($message) {
            $message->to('admin@example.com')->subject('Nuevo post');
        });

        session()->flash('success', 'Post creado.');
        $this->resetForm();
        $this->loadArticles();
    }

    public function edit($id)
    {
        $article = Article::with(['tags', 'collaborators'])->findOrFail($id);

        if (
            auth()->id() !== $article->user_id &&
            !auth()->user()->hasRole('admin') &&
            !$article->collaborators->contains(auth()->id())
        ) {
            abort(403);
        }

        $this->editing = true;
        $this->article_id = $article->id;
        $this->title = $article->title;
        $this->content = $article->content;
        $this->category_id = $article->category_id;
        $this->tags = $article->tags->pluck('id')->map(fn($id) => (string) $id)->toArray();
        $this->usage_types = $article->tags->pluck('pivot.usage_type', 'id')->toArray();
        $this->collaborators = $article->collaborators->pluck('id')->toArray();
    }

    public function update()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $article = Article::findOrFail($this->article_id);

        if (
            auth()->id() !== $article->user_id &&
            !auth()->user()->hasRole('admin') &&
            !$article->collaborators->contains(auth()->id())
        ) {
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

        $article->collaborators()->sync($this->collaborators);

        session()->flash('success', 'Post actualizado.');
        $this->resetForm();
        $this->loadArticles();
    }

    public function deleteArticle($id)
    {
        $article = Article::findOrFail($id);

        if (
            auth()->id() !== $article->user_id &&
            !auth()->user()->hasRole('admin') &&
            !$article->collaborators->contains(auth()->id())
        ) {
            abort(403);
        }

        $article->delete();
        session()->flash('success', 'Post borrado.');
        $this->loadArticles();
    }

    public function loadArticles()
    {
        $this->articles = auth()->user()->hasRole('admin')
            ? Article::with('user', 'tags')->latest()->get()
            : Article::where('user_id', auth()->id())
                ->orWhereHas('collaborators', fn ($q) => $q->where('user_id', auth()->id()))
                ->with('user', 'tags')
                ->latest()
                ->get();
    }

    public function collaborators()
    {
        return $this->belongsToMany(User::class, 'article_user', 'article_id', 'user_id');
    }

    public function resetForm()
    {
        $this->editing = false;
        $this->title = '';
        $this->content = '';
        $this->category_id = '';
        $this->tags = [];
        $this->usage_types = [];
        $this->collaborators = [];
        $this->article_id = null;
    }

    public function render()
    {
        return view('livewire.article-crud');
    }
}
