<div>
    <h3>Comentario</h3>

    @auth
        <form wire:submit.prevent="addComment">
            <textarea wire:model="content" class="w-full p-2 border rounded"></textarea>
            <button type="submit" class="mt-2 px-4 py-1 bg-blue-500 text-white rounded">Comentar</button>
        </form>
    @else
        <p>Inicia sesion.</p>
    @endauth

    <div class="mt-4 space-y-2">
        @foreach ($comments as $comment)
            <div class="p-2 border rounded">
                <strong>{{ $comment->user->name }}</strong>
                <p>{{ $comment->content }}</p>
                <small class="text-gray-500">{{ $comment->created_at->diffForHumans() }}</small>
            </div>
        @endforeach
    </div>
</div>

