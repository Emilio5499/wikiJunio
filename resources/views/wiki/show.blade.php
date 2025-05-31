@extends('layouts.app')

@section('content')
    <h1 class="text-3xl font-bold mb-4">{{ $article->title }}</h1>

    <p class="text-gray-600 mb-2">
        Por {{ $article->user->name ?? 'Anónimo' }} • {{ $article->created_at->format('d/m/Y') }}
    </p>

    <div class="prose max-w-none mb-4">
        {!! $article->content !!}
    </div>

    @if ($article->tags->count())
        <div class="mb-4">
            <strong>Tags:</strong>
            @foreach ($article->tags as $tag)
                <span class="inline-block bg-blue-100 text-blue-700 px-2 py-1 text-xs rounded">
                    {{ $tag->name }}
                </span>
            @endforeach
        </div>
    @endif

    <a href="{{ route('wiki.index') }}" class="text-blue-600 underline">
        Volver
    </a>
@endsection
