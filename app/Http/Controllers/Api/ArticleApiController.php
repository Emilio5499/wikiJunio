<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Article;

class ArticleApiController extends Controller
{
    public function index()
    {
        $articles = auth()->user()->articles()->with('category', 'collaborators')->get();

        return response()->json($articles);
    }

    public function show($id)
    {
        $article = auth()->user()->articles()->with('category', 'collaborators')->findOrFail($id);

        return response()->json($article);
    }

    public function update(Request $request, $id)
    {
        $article = auth()->user()->articles()->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $article->update($validated);

        return response()->json([
            'message' => 'post actualizado',
            'article' => $article
        ]);
    }

    public function destroy($id)
    {
        $article = auth()->user()->articles()->findOrFail($id);
        $article->delete();

        return response()->json(['message' => 'post borrado']);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $validated['user_id'] = $request->user()->id;

        $article = Article::create($validated);

        return response()->json(
            $article->fresh(['category', 'user']),
            201
        );
    }

    public function downloadPdf($id)
    {
        $article = Article::with('user')->findOrFail($id);

        $pdf = Pdf::loadView('articles.pdf', compact('article'));

        return $pdf->download('article_'.$article->id.'.pdf');
    }

}
