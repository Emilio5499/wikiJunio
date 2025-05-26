<div class="mb-4">
    <label for="{{ $id }}" class="block text-sm font-medium text-gray-700 mb-1">
        {{ $label }}
    </label>
    <textarea id="{{ $id }}"
              name="{{ $name }}"
              rows="{{ $rows ?? 4 }}"
              {{ $attributes->merge(['class' => 'border rounded w-full p-2']) }}>
        {{ old($name, $value ?? '') }}
    </textarea>
    @error($name)
    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
    @enderror
</div>
