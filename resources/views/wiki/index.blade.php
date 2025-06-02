@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Posts</h1>

    @auth
        <div class="flex justify-between items-center mb-6">
            <a href="{{ route('articles.create') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Crear post
            </a>

            <a href="{{ route('articles.downloadAll') }}"
               class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Descargar todos
            </a>
        </div>
    @endauth

    <form method="GET" class="mb-4">
        <input type="text" name="search" value="{{ request('search') }}"
               class="p-2 border rounded w-full max-w-md">
    </form>

    @forelse ($articulos as $article)
        <div class="mb-6 p-4 bg-white shadow rounded">
            <h2 class="text-xl font-semibold">
                <a href="{{ route('wiki.show', $article) }}">{{ $article->title }}</a>
            </h2>
            <p class="text-sm text-gray-600 mb-2">
                Por {{ $article->user->name ?? 'Anonimo' }} - {{ $article->created_at->format('d/m/Y') }}
            </p>
            <p>{{ Str::limit(strip_tags($article->content), 120) }}</p>
            @if ($article->tags->count())
                <div class="mt-2">
                    @foreach ($article->tags as $tag)
                        <span class="inline-block bg-blue-200 text-blue-800 px-2 py-1 text-xs rounded">
                            {{ $tag->name }}
                        </span>
                    @endforeach
                </div>
            @endif
        </div>
    @empty
        <p>No hay posts publicos</p>
    @endforelse

    {{ $articulos->links() }}
@endsection
