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
        $orden = $request->input('orden', 'recientes'); // valor por defecto

        $articulos = Article::query()
            ->when($orden === 'titulo_asc', fn($q) => $q->orderBy('title', 'asc'))
            ->when($orden === 'titulo_desc', fn($q) => $q->orderBy('title', 'desc'))
            ->when($orden === 'recientes', fn($q) => $q->orderByDesc('created_at')) // ya era tu default
            ->when($categoriaId, fn($q) => $q->porCategoria($categoriaId))
            ->when($minComentarios, fn($q) => $q->conMuchosComentarios($minComentarios))
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


    public function show(Article $article)
    {
        $article->load(['user', 'category', 'tags', 'comentarios.user']);

        return view('wiki.show', compact('article'));
    }
}
