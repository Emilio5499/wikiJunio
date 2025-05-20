<?php

namespace App\Livewire;

use App\Models\Comentario;
use Livewire\Component;

class CommentCrud extends Component
{
    public $articleId;
    public $content;
    public $comments;

    public function mount($articleId)
    {
        $this->articleId = $articleId;
        $this->loadComments();
    }

    public function loadComments()
    {
        $this->comments = Comentario::with('user')
            ->where('article_id', $this->articleId)
            ->latest()
            ->get();
    }

    public function addComment()
    {
        $this->validate([
            'content' => 'required|string|min:2',
        ]);

        Comentario::create([
            'user_id' => auth()->id(),
            'article_id' => $this->articleId,
            'content' => $this->content,
        ]);

        $this->content = '';
        $this->loadComments();
    }

    public function render()
    {
        return view('livewire.comment-crud');
    }
}
