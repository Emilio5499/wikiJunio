@if (session()->has('success'))
    <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

<div>
    <form wire:submit.prevent="save" class="mb-4">
        <input type="text" wire:model="name" placeholder="Nombre de la categoría" class="border p-2 rounded">
        <button class="bg-green-600 text-white px-3 py-1 rounded ml-2">
            {{ $editingId ? 'Actualizar' : 'Crear' }}
        </button>
    </form>

    <ul>
        @forelse ($categories as $category)
            <li class="flex justify-between items-center mb-2">
                <span>{{ $category->name }}</span>
                <div>
                    <button wire:click="edit({{ $category->id }})" class="text-blue-600 text-sm hover:underline">
                        Editar
                    </button>
                    <button wire:click="delete({{ $category->id }})" class="text-red-600 text-sm hover:underline ml-2">
                        Eliminar
                    </button>
                </div>
            </li>
        @empty
            <li class="text-gray-500">No hay categorías registradas.</li>
        @endforelse
    </ul>
</div>
