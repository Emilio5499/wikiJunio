<div class="space-y-4">
    <h3 class="text-lg font-bold">Tags del post</h3>

    <form wire:submit.prevent="addTag">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <x-select label="Etiqueta" name="tag_id" id="tag_id" wire:model="tag_id">
                <option value="">Selecciona tag</option>
                @foreach ($availableTags as $tag)
                    <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                @endforeach
            </x-select>

            <x-select label="Uso de la etiqueta" name="usage_type" id="usage_type" wire:model="usage_type">
                <option value="">Elige uso</option>
                <option value="nuevo_post">Nuevo post</option>
                <option value="debate">Debate</option>
                <option value="spoiler">Spoiler</option>
            </x-select>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                Añadir
            </button>
        </div>
    </form>

    <ul class="space-y-2 mt-4">
        @foreach ($assignedTags as $tag)
            <li class="border p-2 rounded flex justify-between items-center">
                <div>
                    <strong>{{ $tag->name }}</strong>
                    <span class="text-sm text-gray-600">({{ ucfirst(str_replace('_', ' ', $tag->pivot->usage_type)) }})</span>
                </div>
                <button wire:click="removeTag({{ $tag->id }})" class="text-red-500 text-sm">Eliminar</button>
            </li>
        @endforeach
    </ul>
</div>
