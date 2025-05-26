<div>
    <form wire:submit.prevent="create" class="space-y-2">
        <x-input label="Título" name="title" id="title" :value="$article->title ?? ''" />
        <x-textarea label="Contenido" name="content" id="content" :value="$article->content ?? ''" />
        <x-select label="Categoría" name="category_id" id="category_id" wire:model="category_id">
            <option value="">Elegir categoría</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </x-select>

        <button type="submit"
                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            {{ $editing ? 'Actualizar' : 'Crear post' }}
        </button>
    </form>

    <hr class="my-4">

    <div>
        @foreach($articles as $article)
            <div class="border p-4 mb-2 rounded">
                <h2 class="font-bold">{{ $article->title }}</h2>
                <p>{{ $article->content }}</p>
                <small>Por: {{ $article->user->name }}</small>
            </div>
        @endforeach
    </div>
</div>
