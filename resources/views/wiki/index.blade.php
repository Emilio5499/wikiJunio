@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h1 class="text-2xl font-bold mb-4">{{ __('messages.articles') }}</h1>

        <form method="GET" class="mb-4">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="{{ __('messages.search') }}"
                   class="form-control w-full p-2 border rounded" />
        </form>

        @forelse ($articulos as $article)
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <h2 class="text-xl font-semibold mb-1">
                        <a href="{{ route('wiki.show', $article) }}">{{ $article->title }}</a>
                    </h2>

                    <p class="text-sm text-gray-600 mb-2">
                        {{ __('messages.creator') }}: {{ $article->user->name ?? 'Anonimo' }}
                        • {{ $article->created_at->format('d/m/Y') }}
                    </p>

                    <p>{{ Str::limit(strip_tags($article->content), 150) }}</p>

                    @if ($article->tags->count())
                        <div class="mt-2">
                            @foreach ($article->tags as $tag)
                                <span class="badge bg-primary">{{ $tag->name }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <p>{{ __('messages.no_articles_found') ?? 'No se encontraron artículos.' }}</p>
        @endforelse

        <div class="mt-4">
            {{ $articulos->links() }}
        </div>
    </div>
@endsection
