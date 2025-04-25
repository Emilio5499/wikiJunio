<div>
    <form wire:submit.prevent="create" class="space-y-2">
        <input wire:model="title" type="text" placeholder="Título" class="border rounded w-full p-2">
        <textarea wire:model="content" placeholder="Contenido" class="border rounded w-full p-2"></textarea>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Crear Artículo</button>
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
