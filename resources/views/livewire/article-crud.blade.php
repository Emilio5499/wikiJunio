<div class="max-w-4xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">
        {{ $editing ? 'Editar post' : 'Nuevo post' }}
    </h1>

    @if (session()->has('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="{{ $editing ? 'update' : 'create' }}" class="space-y-4 bg-white p-6 rounded shadow">
        <x-input label="Título" name="title" id="title" wire:model.defer="title" />

        <x-textarea label="Contenido" name="content" id="content" wire:model.defer="content" />

        <x-select label="Categoría" name="category_id" id="category_id" wire:model="category_id">
            <option value="">Categoria</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </x-select>

        <div>
            <label class="block font-semibold mb-1">Tags</label>
            @foreach ($availableTags as $index => $tag)
                <div class="flex items-center space-x-4 mb-2">
                    <input type="checkbox" wire:model="tags" value="{{ $tag->id }}" id="tag-{{ $tag->id }}">
                    <label for="tag-{{ $tag->id }}">{{ $tag->name }}</label>

                    @if (in_array($tag->id, $tags))
                        <select wire:model="usage_types.{{ array_search($tag->id, $tags) }}"
                                class="border rounded p-1 text-sm">
                            <option value="post nuevo">Post nuevo</option>
                            <option value="debate">Debate</option>
                            <option value="spoiler">Spoiler</option>
                        </select>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="flex space-x-4">
            <button type="submit"
                    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
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

    {{-- Divider --}}
    <hr class="my-6">

    <div>
        <h2 class="text-xl font-semibold mb-2">Tus posts</h2>

        @forelse($articles as $article)
            <div class="border p-4 mb-3 rounded bg-gray-50">
                <h3 class="font-bold text-lg">{{ $article->title }}</h3>
                <p class="text-sm text-gray-700 mb-1">{{ Str::limit($article->content, 120) }}</p>
                <small class="text-gray-600">Por: {{ $article->user->name }}</small>
                <div class="mt-2">
                    <button wire:click="edit({{ $article->id }})"
                            class="text-blue-600 text-sm hover:underline">Editar</button>
                </div>
            </div>
        @empty
            <p class="text-gray-600">No hay posts</p>
        @endforelse
    </div>
</div>
