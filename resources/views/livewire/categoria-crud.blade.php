<div>
    <form wire:submit.prevent="{{ $categoryIdBeingEdited ? 'update' : 'create' }}" class="space-y-2">
        <input wire:model="name" type="text" placeholder="Nombre de la Categoría" class="border rounded w-full p-2">
        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">
            {{ $categoryIdBeingEdited ? 'Actualizar' : 'Crear' }}
        </button>
    </form>

    <hr class="my-6">

    <div class="space-y-4">
        @foreach($categories as $category)
            <div class="flex justify-between items-center border p-2 rounded">
                <div>{{ $category->name }}</div>
                <div class="space-x-2">
                    <button wire:click="edit({{ $category->id }})" class="bg-blue-400 text-white px-2 py-1 rounded">Editar</button>
                    <button wire:click="delete({{ $category->id }})" class="bg-red-400 text-white px-2 py-1 rounded">Eliminar</button>
                </div>
            </div>
        @endforeach
    </div>
</div>
