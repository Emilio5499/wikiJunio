<div class="mb-4">
    <label for="{{ $id }}" class="block text-sm font-medium text-gray-700 mb-1">
        {{ $label }}
    </label>

    <select
        id="{{ $id }}"
        name="{{ $name }}"
        {{ $attributes->merge(['class' => 'border rounded w-full p-2']) }}
    >
        {{ $slot }}
    </select>

    @error($name)
    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
    @enderror
</div>
