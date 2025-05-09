<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ArticlePdfController extends Controller
{
    public function download(Article $article)
    {
        $pdf = Pdf::loadView('pdfs.article', compact('article'));
        return $pdf->download('post-' . $article->id . '.pdf');
    }
}
