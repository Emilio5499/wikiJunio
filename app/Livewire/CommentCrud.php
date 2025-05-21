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
        $this->cargaComentario();
    }

    public function cargaComentario()
    {
        $this->comments = Comentario::with('user')
            ->where('article_id', $this->articleId)
            ->latest()
            ->get();
    }

    public function creaComentario()
    {
        if (!auth()->check()) {
            abort(403);
        }

        $this->validate([
            'content' => 'required|string|min:2',
        ]);

        Comentario::create([
            'user_id' => auth()->id(),
            'article_id' => $this->articleId,
            'content' => $this->content,
        ]);

        $this->content = '';
        $this->cargaComentario();
    }

    public function borraComentario($id)
    {
        $comentario = Comentario::findOrFail($id);
        $user = auth()->user();

        if ($comentario->user_id !== $user->id && !$user->hasRole('admin')) {
            abort(403);
        }

        $comentario->delete();
        $this->cargaComentario();
    }

    public function editaComentario($id, $newContent)
    {
        $comentario = \App\Models\Comentario::findOrFail($id);
        $user = auth()->user();

        if ($comentario->user_id !== $user->id) {
            abort(403);
        }

        $comentario->update([
            'content' => $newContent,
        ]);

        $this->cargaComentario();
    }

    public function render()
    {
        return view('livewire.comment-crud');
    }
}
