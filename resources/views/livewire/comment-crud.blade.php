<div>
    <h3>Comentario</h3>

    @auth
        <form wire:submit.prevent="creaComentario">
            <x-textarea name="content" label="Comentario" wire:model="content" />
            <button type="submit" class="mt-2 px-4 py-1 bg-blue-500 text-white rounded">Comentar</button>
        </form>
    @else
        <p>Inicia sesion.</p>
    @endauth

    @foreach($comments as $comment)
        <div class="border p-3 mb-3 rounded bg-gray-100">
            <p class="text-sm text-gray-800">
                <strong>{{ $comment->user->name ?? 'Anónimo' }}</strong>:
            </p>

            @if($editingId === $comment->id)
                <textarea wire:model="editContent" class="w-full p-2 border rounded mb-2"></textarea>
                <div class="flex gap-2">
                    <button wire:click="updateComment"
                            class="bg-blue-600 text-white px-2 py-1 text-sm rounded">
                        Guardar
                    </button>
                    <button wire:click="$set('editingId', null)"
                            class="bg-gray-400 text-white px-2 py-1 text-sm rounded">
                        Cancelar
                    </button>
                </div>
            @else
                <p class="mb-1">{{ $comment->content }}</p>

                @if(auth()->id() === $comment->user_id || auth()->user()->hasRole('admin'))
                    <div class="text-sm text-right">
                        <button wire:click="startEdit({{ $comment->id }})"
                                class="text-blue-600 hover:underline mr-2">
                            Editar
                        </button>

                        <button wire:click="deleteComment({{ $comment->id }})"
                                onclick="return confirm('¿Borrar este comentario?')"
                                class="text-red-600 hover:underline">
                            Borrar
                        </button>
                    </div>
                @endif
            @endif
        </div>
    @endforeach


</div>

