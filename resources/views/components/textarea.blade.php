@props(['label', 'name'])

<div>
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">{{ $label }}</label>
    <textarea
        id="{{ $name }}"
        name="{{ $name }}"
        {{ $attributes->merge(['class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm']) }}
    >{{ $attributes['wire:model.defer'] ? '' : $attributes['value'] ?? '' }}</textarea>
</div>
