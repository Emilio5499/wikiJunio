@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-4">{{ __('titles.posts') }}</h1>

    @auth
        <div class="flex justify-between items-center mb-6">
            <a href="{{ route('articles.create') }}"
               class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                {{ __('buttons.create_new') }}
            </a>

            <a href="{{ route('articles.downloadAll') }}"
               class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                {{ __('buttons.download_all') }}
            </a>
        </div>
    @endauth

    <div class="mb-4">
        <a href="{{ route('lang.switch', 'es') }}" class="px-3 py-1 bg-gray-300 rounded mr-2">ES</a>
        <a href="{{ route('lang.switch', 'en') }}" class="px-3 py-1 bg-gray-300 rounded">EN</a>
    </div>

    <form method="GET" class="mb-6 flex flex-wrap gap-3 items-center">
        <input type="text" name="search" value="{{ $busqueda ?? '' }}"
               placeholder="{{ __('forms.search_placeholder') }}"
               class="p-2 border rounded w-48">

        <select name="categoria" class="p-2 border rounded">
            <option value="">{{ __('filters.all_categories') }}</option>
            @foreach (\App\Models\Category::all() as $categoria)
                <option value="{{ $categoria->id }}" {{ (request('categoria') == $categoria->id) ? 'selected' : '' }}>
                    {{ $categoria->name }}
                </option>
            @endforeach
        </select>

        <select name="min" class="p-2 border rounded">
            <option value="0" {{ request('min') == 0 ? 'selected' : '' }}>{{ __('filters.all_comments') }}</option>
            <option value="1" {{ request('min') == 1 ? 'selected' : '' }}>1 {{ __('filters.or_more') }}</option>
            <option value="3" {{ request('min') == 3 ? 'selected' : '' }}>3 {{ __('filters.or_more') }}</option>
            <option value="5" {{ request('min') == 5 ? 'selected' : '' }}>5 {{ __('filters.or_more') }}</option>
        </select>

        <select name="orden" class="p-2 border rounded">
            <option value="recientes" {{ request('orden') === 'recientes' ? 'selected' : '' }}>{{ __('filters.most_recent') }}</option>
            <option value="titulo_asc" {{ request('orden') === 'titulo_asc' ? 'selected' : '' }}>{{ __('filters.title_asc') }}</option>
            <option value="titulo_desc" {{ request('orden') === 'titulo_desc' ? 'selected' : '' }}>{{ __('filters.title_desc') }}</option>
        </select>

        <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            {{ __('buttons.filter') }}
        </button>
    </form>

    @forelse ($articulos as $article)
        <div class="mb-6 p-4 bg-white shadow rounded">
            <h2 class="text-xl font-semibold">
                <a href="{{ route('wiki.show', $article) }}">{{ $article->title }}</a>
            </h2>
            <p class="text-sm text-gray-600 mb-2">
                {{ __('forms.by') }} {{ $article->user->name ?? __('posts.anonymous') }} - {{ $article->created_at->format('d/m/Y') }}
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
        <p>{{ __('posts.no_posts') }}</p>
    @endforelse

    {{ $articulos->links() }}
@endsection
