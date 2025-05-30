<?php
namespace App\Http\Livewire;

use App\Events\ArticuloCreado;
use Livewire\Component;
use App\Models\Article;

class ArticleCrud extends Component
{
    public $title, $content;

    public function render()
    {
        return view('livewire.article-crud', [
            'articles' => Article::latest()->get()
        ]);
    }

    public function create()
    {
        $this->validate([
            'title' => 'required',
            'content' => 'required',
        ]);

        auth()->user()->articles()->create([
            'title' => $this->title,
            'content' => $this->content,
        ]);

        $this->reset(['title', 'content']);

        $article = auth()->user()->articles()->create([
            'title' => $this->title,
            'content' => $this->content,
        ]);
        event(new ArticuloCreado($article));

    }
    public function mount()
    {
        if (!auth()->user()->can('manage articles')) {
            abort(403);
        }
    }

}
