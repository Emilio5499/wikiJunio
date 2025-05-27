<div class="mb-4">
    <label for="{{ $id }}" class="block text-sm font-medium text-gray-700 mb-1">
        {{ $label }}
    </label>

    <input
        type="{{ $type }}"
        id="{{ $id }}"
        name="{{ $name }}"
        value="{{ old($name, $value) }}"
        {{ $attributes->merge(['class' => 'border rounded w-full p-2']) }}
    />

    @error($name)
    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
    @enderror
</div>
