<div class="space-y-4">
    <h3 class="text-lg font-bold">Etiquetas del post</h3>

    <form wire:submit.prevent="addTag">
        <div class="flex gap-4 items-end">
            {{-- Selector de tag --}}
            <div class="flex flex-col">
                <label for="tag_id">Tag</label>
                <select wire:model="tag_id" class="border rounded p-1" id="tag_id">
                    <option value="">Elige un tag</option>
                    @foreach ($availableTags as $tag)
                        <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col">
                <label for="usage_type">Uso</label>
                <select wire:model="usage_type" class="border rounded p-1" id="usage_type">
                    <option value="">Elige tag</option>
                    <option value="nuevo_post">Nuevo post</option>
                    <option value="debate">Debate</option>
                    <option value="spoiler">Spoiler</option>
                </select>
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                Nuevo
            </button>
        </div>
    </form>

    <ul class="space-y-2">
        @foreach ($assignedTags as $tag)
            <li class="border p-2 rounded flex justify-between items-center">
                <div>
                    <strong>{{ $tag->name }}</strong>
                    <span class="text-sm text-gray-600">({{ $tag->pivot->usage_type }})</span>
                </div>
                <button wire:click="removeTag({{ $tag->id }})" class="text-red-500 text-sm">Borrar</button>
            </li>
        @endforeach
    </ul>
</div>
