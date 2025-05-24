<?php

namespace App\Models;

use Illuminate\Container\Attributes\Tag;
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
        return $this->belongsToMany(Tag::class)
            ->withPivot('usage_type')
            ->withTimestamps();
    }

}
