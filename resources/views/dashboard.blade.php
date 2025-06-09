@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto p-6">
        {{-- Selector de idioma --}}
        <div class="mb-4">
            <a href="{{ route('lang.switch', 'es') }}" class="px-3 py-1 bg-gray-300 rounded mr-2">ES</a>
            <a href="{{ route('lang.switch', 'en') }}" class="px-3 py-1 bg-gray-300 rounded">EN</a>
        </div>

        <h1 class="text-2xl font-bold mb-6">{{ __('dashboard.welcome') }}, {{ Auth::user()->name }}</h1>

        <div class="mb-8">
            <h2 class="text-xl font-semibold mb-3">{{ __('dashboard.latest_posts') }}</h2>

            @forelse (Auth::user()->articles()->latest()->take(3)->get() as $article)
                <div class="border p-3 mb-2 rounded bg-white shadow">
                    <a href="{{ route('wiki.show', $article) }}" class="font-bold">{{ $article->title }}</a>
                    <p class="text-sm text-gray-600">{{ $article->created_at->format('d/m/Y H:i') }}</p>
                </div>
            @empty
                <p class="text-gray-600">{{ __('dashboard.no_posts_yet') }}</p>
            @endforelse

            <a href="{{ route('wiki.index') }}" class="text-blue-600 hover:underline mt-2 inline-block">{{ __('dashboard.view_all_posts') }}</a>
        </div>

        <div class="mb-8">
            <h2 class="text-xl font-semibold mb-3">{{ __('dashboard.latest_comments') }}</h2>

            @forelse (Auth::user()->comments()->latest()->take(3)->get() as $comentario)
                <div class="border p-3 mb-2 rounded bg-white">
                    <p class="text-gray-800">{{ $comentario->content }}</p>
                    <p class="text-sm text-gray-600">
                        {{ __('dashboard.in') }}:
                        <a href="{{ route('wiki.show', $comentario->article) }}" class="text-blue-500 hover:underline">
                            {{ $comentario->article->title }}
                        </a>
                        – {{ $comentario->created_at->diffForHumans() }}
                    </p>
                </div>
            @empty
                <p class="text-gray-600">{{ __('dashboard.no_comments_yet') }}</p>
            @endforelse
        </div>
    </div>
@endsection
