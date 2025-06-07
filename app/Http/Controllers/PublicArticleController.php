<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Models\Tag;

class PublicArticleController extends Controller
{
    public function index(Request $request)
    {
        $categoriaId = $request->input('categoria');
        $minComentarios = $request->input('min', 0);
        $busqueda = $request->input('search');
        $orden = $request->input('orden', 'recientes');

        $articulos = Article::withCount('comentarios')
        ->when($orden === 'titulo_asc', fn($q) => $q->orderBy('title', 'asc'))
            ->when($orden === 'titulo_desc', fn($q) => $q->orderBy('title', 'desc'))
            ->when($orden === 'recientes', fn($q) => $q->orderByDesc('created_at'))
            ->when($categoriaId, fn($q) => $q->porCategoria($categoriaId))
            ->when($minComentarios, fn($q) => $q->having('comentarios_count', '>=', $minComentarios)) // 👈 usar having
            ->when($busqueda, function ($q) use ($busqueda) {
                $q->where(function ($query) use ($busqueda) {
                    $query->where('title', 'like', "%{$busqueda}%")
                        ->orWhere('content', 'like', "%{$busqueda}%");
                });
            })
            ->with(['category', 'user', 'tags'])
            ->paginate(10);

        return view('wiki.index', compact('articulos', 'categoriaId', 'minComentarios', 'busqueda', 'orden'));
    }

    public function edit(Article $article)
    {
        return view('articles.edit', compact('article'));
    }

    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'array',
        ]);

        $article->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'category_id' => $validated['category_id'],
        ]);

        if (isset($validated['tags'])) {
            $article->tags()->sync($validated['tags']);
        }

        return redirect()->route('wiki.index')->with('success', 'Post actualizado');
    }

    public function show(Article $article)
    {
        $article->load(['user', 'category', 'tags', 'comentarios.user']);

        return view('wiki.show', compact('article'));
    }
}
