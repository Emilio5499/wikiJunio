<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Article;

class ArticleApiController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $validated['user_id'] = $request->user()->id;

        $article = Article::create($validated);

        return response()->json($article, 201);
    }

    public function downloadPdf($id)
    {
        $article = Article::with('user')->findOrFail($id);

        $pdf = Pdf::loadView('articles.pdf', compact('article'));

        return $pdf->download('article_'.$article->id.'.pdf');
    }

}
