<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryApiController extends Controller
{
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

    public function update(Request $request, $id){

    }

    public function destroy($id){

    }

    public function store(Request $request){

    }

}
