<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class PublicArticleController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $articles = Article::query()
            ->when($search, fn ($query) =>
            $query->where('title', 'like', "%$search%")
                ->orWhere('content', 'like', "%$search%"))
            ->latest()
            ->paginate(10);

        return view('public.articles.index', compact('articles', 'search'));
    }

    public function show(Article $article)
    {
        return view('public.articles.show', compact('article'));
    }
}
