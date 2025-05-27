<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class PublicArticleController extends Controller
{
    public function index(Request $request)
    {
        $categoriaId = $request->input('categoria'); // ej: /wiki?categoria=2
        $minComentarios = $request->input('min', 0); // ej: /wiki?min=5

        $articulos = Article::publicadosRecientes()
            ->when($categoriaId, fn($q) => $q->porCategoria($categoriaId))
            ->when($minComentarios, fn($q) => $q->conMuchosComentarios($minComentarios))
            ->with(['category', 'user', 'tags'])
            ->paginate(10);

        return view('wiki.index', compact('articulos'));
    }

    public function show(Article $article)
    {
        $article->load(['user', 'category', 'tags', 'comentarios.user']);

        return view('wiki.show', compact('article'));
    }
}
