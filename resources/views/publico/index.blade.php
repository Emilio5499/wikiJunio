<?php
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Artículos públicos</h1>

        <form method="GET">
            <input type="text" name="search" value="{{ $search }}" placeholder="Buscar artículos..." class="form-control mb-3">
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
@endsection
