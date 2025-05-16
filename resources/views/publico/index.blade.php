<?php
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Post publicos</h1>

        <form method="GET">
            <input type="text" name="search" value="{{ $search }}" class="form-control mb-3">
        </form>

        @foreach ($articles as $article)
            <div class="card mb-2">
                <div class="card-body">
                    <h4><a href="{{ route('public.articles.show', $article) }}">{{ $article->title }}</a></h4>
                    <p>{{ Str::limit($article->content, 150) }}</p>
                </div>
            </div>
        @endforeach

        {{ $articles->withQueryString()->links() }}
    </div>

    @foreach ($articles as $article)
        <div class="card mb-3">
            <div class="card-body">
                <h4>
                    <a href="{{ route('public.articles.show', $article) }}">
                        {{ $article->title }}
                    </a>
                </h4>
                <p class="text-muted">
                    Por {{ $article->user->name ?? 'Anónimo' }} — {{ $article->created_at->format('d/m/Y') }}
                </p>
                <p>{{ Str::limit(strip_tags($article->content), 120) }}</p>

                @if ($article->tags->count())
                    <div>
                        @foreach ($article->tags as $tag)
                            <span class="badge bg-primary">{{ $tag->name }}</span>
                        @endforeach
                    </div>
                    <h1>{{ __('messages.articles') }}</h1>

                    <input type="text" placeholder="{{ __('messages.search') }}">
                    <label>{{ __('messages.title') }}</label>

                    <button>{{ __('messages.save') }}</button>
                    <a href="#">{{ __('messages.cancel') }}</a>

                @endif
            </div>
        </div>
    @endforeach


@endsection
