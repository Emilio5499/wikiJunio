<?php

namespace App\Models;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'user_id',
        'category_id',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function comments()
    {
        return $this->hasMany(Comentario::class);
    }

    public function comentarios()
    {
        return $this->hasMany(Comentario::class);
    }

    public function collaborators()
    {
        return $this->belongsToMany(User::class, 'article_user')
            ->withPivot('role_in_article', 'joined_at')
            ->withTimestamps();
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeWithCollaborators(Builder $query): Builder
    {
        return $query->whereHas('collaborators');
    }

    public function scopeSearch(Builder $query, $term): Builder
    {
        return $query->where(function ($q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
                ->orWhere('content', 'like', "%{$term}%");
        });
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class)->withPivot('usage_type');
    }

    public function scopePublicadosRecientes($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeMuchosComentarios($query, $cantidad = 1)
    {
        return $query->withCount('comentarios')
            ->having('comentarios_count', '>=', $cantidad)
            ->orderByDesc('comentarios_count');
    }


    public function scopePorCategoria($query, $categoriaId)
    {
        return $query->where('category_id', $categoriaId);
    }

}
