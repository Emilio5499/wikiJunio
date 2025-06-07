<div class="max-w-4xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">
        {{ $editing ? 'Editar post' : 'Nuevo post' }}
    </h1>

    @if (session()->has('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">
            <ul class="text-sm list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form wire:submit.prevent="{{ $editing ? 'update' : 'create' }}" class="space-y-4 bg-white p-6 rounded shadow">
        <x-input label="Título" name="title" id="title" wire:model.defer="title" />

        <x-textarea label="Contenido" name="content" id="content" wire:model.defer="content" />

        <x-select label="Categoría" name="category_id" id="category_id" wire:model="category_id">
            <option value="">Elegir categoria</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </x-select>

        @if ($availableTags->count())
            <div>
                <label class="block font-semibold mb-2">Tags</label>

                @foreach ($availableTags as $tag)
                    <div class="flex items-center space-x-4 mb-2">
                        <input type="checkbox" wire:model="tags" value="{{ $tag->id }}" id="tag-{{ $tag->id }}">
                        <label for="tag-{{ $tag->id }}">{{ $tag->name }}</label>

                        @if (collect($tags)->contains((string) $tag->id))
                            <select wire:model="usage_types.{{ $tag->id }}" class="border rounded p-1 text-sm">
                                <option value="">Tipo de uso</option>
                                <option value="post nuevo">Post nuevo</option>
                                <option value="debate">Debate</option>
                                <option value="spoiler">Spoiler</option>
                            </select>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        <div class="flex space-x-4">
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                {{ $editing ? 'Actualizar post' : 'Crear post' }}
            </button>

            @if ($editing)
                <button type="button" wire:click="resetForm"
                        class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500">
                    Cancelar
                </button>
            @endif
        </div>
    </form>

    <hr class="my-6">

    <div>
        <h2 class="text-xl font-semibold mb-2">Posts</h2>

        @forelse($articles as $article)
            <div class="border p-4 mb-3 rounded bg-gray-50 relative">
                <h3 class="font-bold text-lg">{{ $article->title }}</h3>
                <p class="text-sm text-gray-700 mb-1">{{ Str::limit($article->content, 120) }}</p>
                <small class="text-gray-600">Por: {{ $article->user->name ?? 'Desconocido' }}</small>

                @if (auth()->id() === $article->user_id || auth()->user()->hasRole('admin'))
                    <div class="absolute top-2 right-2 flex space-x-2">
                        <button wire:click="edit({{ $article->id }})"
                                class="text-blue-600 text-sm hover:underline">
                            Editar
                        </button>
                        <button wire:click="deleteArticle({{ $article->id }})"
                                onclick="return confirm('¿Borrar este post?')"
                                class="text-red-600 text-sm hover:underline">
                            Borrar
                        </button>
                    </div>
                @endif
            </div>
        @empty
            <p class="text-gray-600">No hay posts</p>
        @endforelse
    </div>
</div>
