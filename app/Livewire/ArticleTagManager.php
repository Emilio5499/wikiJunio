<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Article;
use App\Models\Tag;
use Illuminate\Support\Facades\Validator;

class ArticleTagManager extends Component
{
    public $articleId;
    public $tag_id;
    public $usage_type;

    public $availableTags = [];
    public $assignedTags;

    public function mount($articleId)
    {
        $this->articleId = $articleId;
        $this->loadAvailableTags();
        $this->loadAssignedTags();
    }

    public function loadAvailableTags()
    {
        $this->availableTags = Tag::orderBy('name')->get();
    }

    public function loadAssignedTags()
    {
        $article = Article::with('tags')->findOrFail($this->articleId);
        $this->assignedTags = $article->tags;
    }

    public function addTag()
    {
        Validator::make([
            'tag_id' => $this->tag_id,
            'usage_type' => $this->usage_type,
        ], [
            'tag_id' => 'required|exists:tags,id',
            'usage_type' => 'required|in:nuevo_post,debate,spoiler',
        ])->validate();

        $article = Article::findOrFail($this->articleId);

        // Evitar duplicado
        if ($article->tags()->where('tag_id', $this->tag_id)->exists()) {
            $this->addError('tag_id', 'Esta etiqueta ya está asociada.');
            return;
        }

        $article->tags()->attach($this->tag_id, [
            'usage_type' => $this->usage_type,
        ]);

        // Reset campos
        $this->tag_id = null;
        $this->usage_type = null;
        $this->loadAssignedTags();
    }

    public function removeTag($tagId)
    {
        $article = Article::findOrFail($this->articleId);
        $article->tags()->detach($tagId);

        $this->loadAssignedTags();
    }

    public function render()
    {
        return view('livewire.article-tag-manager');
    }
}
