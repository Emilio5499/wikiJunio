<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArticlePdfController extends Controller
{
    public function download(Article $article)
    {
        if (!auth()->check()) {
            abort(403);
        }

        $html = '
            <h1>' . e($article->title) . '</h1>
            <p><strong>Autor:</strong> ' . e($article->user->name ?? 'Anónimo') . '</p>
            <p><strong>Fecha:</strong> ' . $article->created_at->format('d/m/Y') . '</p>
            <hr>
            <div>' . $article->content . '</div>
            <br><br>
            <div style="position:absolute; top:20px; right:20px;">
                <img src="' . public_path('images/pdf-icon.png') . '" width="50"  alt=""/>
            </div>
        ';

        $pdf = Pdf::loadHTML($html);
        $filename = Str::slug($article->title) . '.pdf';

        return $pdf->download($filename);
    }

    public function downloadAll()
    {
        $articles = Article::with(['user', 'tags'])->latest()->get();

        if ($articles->isEmpty()) {
            return redirect()->back()->with('error', 'No hay posts descargables');
        }

        $indexHtml = '<h1 style="text-align: center;">Indice</h1><ul>';
        foreach ($articles as $i => $article) {
            $indexHtml .= "<li><strong>" . ($i + 1) . ".</strong> " . e($article->title) . "</li>";
        }
        $indexHtml .= '</ul><hr><br>';

        $contentHtml = '';
        foreach ($articles as $i => $article) {
            $contentHtml .= "<h2>" . ($i + 1) . ". " . e($article->title) . "</h2>";
            $contentHtml .= "<p><strong>Autor:</strong> " . e($article->user->name ?? 'Anonimo') . "</p>";
            $contentHtml .= "<p><strong>Fecha:</strong> " . $article->created_at->format('d/m/Y') . "</p>";

            if ($article->tags->count()) {
                $tags = $article->tags->pluck('name')->join(', ');
                $contentHtml .= "<p><strong>Tags:</strong> " . e($tags) . "</p>";
            }

            $contentHtml .= "<div>" . $article->content . "</div>";
            $contentHtml .= "<hr><br>";
        }

        $html = $indexHtml . $contentHtml;

        $pdf = Pdf::loadHTML($html)->setPaper('a4', 'portrait');
        return $pdf->download('todos-los-articulos.pdf');
    }

}
