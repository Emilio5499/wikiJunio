<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryApiController extends Controller
{
    /**
     * Esta es la api para categorias
     *
     * Al contrario que la de articulos, esta no tiene contenido
     * Solo tiene un "titulo" o nombre
     *
     * @bodyParam name string required Nombre de la categoria. Example: Coches
     */
    public function index()
    {
        return Category::withCount('articles')->get();
    }

    public function show($id)
    {
        $category = Category::whereHas('articles', function ($query) {
            $query->where('user_id', auth()->id());
        })
            ->with('articles')
            ->findOrFail($id);
        return response()->json($category);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = Category::whereHas('articles', function ($query) {
            $query->where('user_id', auth()->id());
        })
            ->findOrFail($id);

        $category->update([
            'name' => $validated['name'],
        ]);

        return response()->json([
            'category' => $category,
        ]);
    }

    public function destroy($id)
    {
        $category = auth()->article()->category()->findOrFail($id);
        $category->delete();

        return response()->json(['message' => 'categoria borrada']);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $article = Category::create($validated);

        return response()->json(
            201
        );
    }

}
