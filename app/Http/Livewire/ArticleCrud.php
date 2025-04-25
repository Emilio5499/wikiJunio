<?php
namespace App\Http\Livewire;

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
    }
}
