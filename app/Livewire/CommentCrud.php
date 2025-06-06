<?php

namespace App\Livewire;

use App\Models\Comentario;
use Livewire\Component;

class CommentCrud extends Component
{
    public $editingId = null;
    public $editContent = '';
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

    public function startEdit($commentId)
    {
        $comentario = Comentario::findOrFail($commentId);

        if (auth()->id() !== $comentario->user_id && !auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $this->editingId = $comentario->id;
        $this->editContent = $comentario->content;
    }

    public function updateComment()
    {
        $this->validate([
            'editContent' => 'required|string|min:1',
        ]);

        $comentario = Comentario::findOrFail($this->editingId);

        if (auth()->id() !== $comentario->user_id && !auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $comentario->update([
            'content' => $this->editContent,
        ]);

        session()->flash('success', 'Comentario actualizado.');
        $this->editingId = null;
        $this->editContent = '';
        $this->loadComentarios();
    }

    public function deleteComment($id)
    {
        $comentario = Comentario::findOrFail($id);

        if (auth()->id() !== $comentario->user_id && !auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $comentario->delete();

        session()->flash('success', 'Comentario eliminado.');
        $this->loadComentarios();
    }

    public function loadComentarios()
    {
        $this->comments = Comentario::where('article_id', $this->articleId)
            ->orderByDesc('created_at')
            ->get();
    }

    public function render()
    {
        return view('livewire.comment-crud');
    }
}
