@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-4">Editar post</h1>

        <form method="POST" action="{{ route('articles.update', $article) }}">
            @csrf
            @method('PUT')

            <x-input label="Título" name="title" :value="old('title', $article->title)" />

            <x-textarea label="Contenido" name="content">{{ old('content', $article->content) }}</x-textarea>

            <x-select label="Categoría" name="category_id">
                <option value="">Categoría</option>
                @foreach (\App\Models\Category::all() as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id', $article->category_id) == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </x-select>

            <div class="mt-4">
                <label class="block font-semibold mb-2">Tags</label>
                @foreach (\App\Models\Tag::all() as $tag)
                    <div class="flex items-center mb-1">
                        <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                            {{ $article->tags->contains($tag->id) ? 'checked' : '' }}>
                        <span class="ml-2">{{ $tag->name }}</span>
                    </div>
                @endforeach
            </div>

            <button type="submit"
                    class="mt-4 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Guardar
            </button>
        </form>
    </div>
@endsection

