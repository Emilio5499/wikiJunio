@extends('layouts.app')

@section('content')
    <h1 class="text-3xl font-bold mb-6">Posts publicos</h1>

    @auth
        <div class="flex justify-between items-center mb-6">
            <a href="{{ route('articles.create') }}"
               class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Nuevo post
            </a>

            <a href="{{ route('articles.downloadAll') }}"
               class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Descargar todos
            </a>
        </div>
    @endauth

    <form method="GET" class="mb-6 flex flex-wrap gap-4 items-end">
        <input type="text" name="search" value="{{ $busqueda ?? '' }}"
               placeholder="Buscar por título o contenido"
               class="p-2 border rounded w-64">

        <select name="categoria" class="p-2 border rounded">
            <option value="">Todas las categorías</option>
            @foreach (\App\Models\Category::all() as $categoria)
                <option value="{{ $categoria->id }}" {{ request('categoria') == $categoria->id ? 'selected' : '' }}>
                    {{ $categoria->name }}
                </option>
            @endforeach
        </select>

        <select name="min" class="p-2 border rounded">
            <option value="0" {{ request('min') == 0 ? 'selected' : '' }}>Todos los comentarios</option>
            <option value="1" {{ request('min') == 1 ? 'selected' : '' }}>1 o más</option>
            <option value="3" {{ request('min') == 3 ? 'selected' : '' }}>3 o más</option>
            <option value="5" {{ request('min') == 5 ? 'selected' : '' }}>5 o más</option>
        </select>

        <select name="orden" class="p-2 border rounded">
            <option value="recientes" {{ request('orden') === 'recientes' ? 'selected' : '' }}>Más recientes</option>
            <option value="titulo_asc" {{ request('orden') === 'titulo_asc' ? 'selected' : '' }}>Título A-Z</option>
            <option value="titulo_desc" {{ request('orden') === 'titulo_desc' ? 'selected' : '' }}>Título Z-A</option>
        </select>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Filtrar
        </button>
    </form>

    @forelse ($articulos as $article)
        <div class="relative mb-6 p-4 bg-white shadow rounded">

            @auth
                @if (auth()->id() === $article->user_id || auth()->user()->hasRole('admin'))
                    <div class="absolute top-2 right-2 flex space-x-2">
                        <button
                            wire:click="$emit('edit', {{ $article->id }})"
                            class="text-blue-600 text-sm hover:underline"
                        >
                            Editar
                        </button>

                        <button
                            wire:click="$emit('deleteArticle', {{ $article->id }})"
                            onclick="return confirm('¿Borrar post?')"
                            class="text-red-600 text-sm hover:underline"
                        >
                            Borrar
                        </button>
                    </div>
                @endif
            @endauth


            <h2 class="text-xl font-semibold mb-1">
                <a href="{{ route('wiki.show', $article) }}">{{ $article->title }}</a>
            </h2>
            <p class="text-sm text-gray-600 mb-2">
                Por {{ $article->user->name ?? 'Anónimo' }} — {{ $article->created_at->format('d/m/Y') }}
            </p>
            <p class="mb-2">{{ Str::limit(strip_tags($article->content), 150) }}</p>

            @if ($article->tags->count())
                <div class="flex flex-wrap gap-2">
                    @foreach ($article->tags as $tag)
                        <span class="bg-blue-100 text-blue-700 px-2 py-1 text-xs rounded">
                            {{ $tag->name }}
                        </span>
                    @endforeach
                </div>
            @endif
        </div>
    @empty
        <p class="text-gray-600">No hay posts publicos</p>
    @endforelse

    {{ $articulos->links() }}
@endsection

@livewire('article-crud')
