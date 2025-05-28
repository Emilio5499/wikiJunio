@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto py-10">
        <h1 class="text-3xl font-bold mb-6">Publico</h1>

        @foreach ($articles as $article)
            <div class="mb-6 border-b pb-4">
                <h2 class="text-xl font-semibold">{{ $article->title }}</h2>
                <p class="text-gray-700 mt-2">{{ Str::limit($article->content, 200) }}</p>
                <small class="text-gray-500">Por: {{ $article->user->name }}</small>
            </div>
        @endforeach
        <h1>{{ __('messages.articles') }}</h1>

        <input type="text" placeholder="{{ __('messages.search') }}">
        <label>{{ __('messages.title') }}</label>

        <button>{{ __('messages.save') }}</button>
        <a href="#">{{ __('messages.cancel') }}</a>

    </div>
@endsection
<?php
