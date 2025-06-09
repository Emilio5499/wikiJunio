@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-4">
            <a href="{{ route('lang.switch', 'es') }}" class="px-3 py-1 bg-gray-300 rounded mr-2">ES</a>
            <a href="{{ route('lang.switch', 'en') }}" class="px-3 py-1 bg-gray-300 rounded">EN</a>
        </div>

        <h1 class="text-2xl font-bold mb-4">{{ __('edit.title') }}</h1>

        <form method="POST" action="{{ route('articles.update', $article) }}">
            @csrf
            @method('PUT')

            <x-input label="{{ __('edit.title_label') }}" name="title" :value="old('title', $article->title)" />

            <x-textarea label="{{ __('edit.content_label') }}" name="content">{{ old('content', $article->content) }}</x-textarea>

            <x-select label="{{ __('edit.category_label') }}" name="category_id">
                <option value="">{{ __('edit.select_category') }}</option>
                @foreach (\App\Models\Category::all() as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id', $article->category_id) == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </x-select>

            <div class="mt-4">
                <label class="block font-semibold mb-2">{{ __('edit.tags_label') }}</label>
                @foreach (\App\Models\Tag::all() as $tag)
                    <div class="flex items-center mb-1">
                        <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                            {{ $article->tags->contains($tag->id) ? 'checked' : '' }}>
                        <span class="ml-2">{{ $tag->name }}</span>
                    </div>
                @endforeach
            </div>

            <button type="submit"
                    class="mt-4 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                {{ __('buttons.save') }}
            </button>
        </form>
    </div>
@endsection
