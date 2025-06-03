<div>
    <form wire:submit.prevent="save" class="mb-4">
        <input type="text" wire:model="name" placeholder="Nombre del tag" class="border p-2 rounded">
        <button class="bg-blue-600 text-white px-3 py-1 rounded ml-2">
            {{ $editingId ? 'Actualizar' : 'Crear' }}
        </button>
    </form>

    <ul>
        @foreach ($tags as $tag)
            <li class="flex justify-between items-center mb-2">
                <span>{{ $tag->name }}</span>
                <div>
                    <button wire:click="edit({{ $tag->id }})" class="text-blue-500 text-sm">Editar</button>
                    <button wire:click="delete({{ $tag->id }})" class="text-red-500 text-sm ml-2">Eliminar</button>
                </div>
            </li>
        @endforeach
    </ul>
</div>
