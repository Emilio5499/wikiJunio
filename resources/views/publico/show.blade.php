<?php
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $article->title }}</h1>
        <p>{!! nl2br(e($article->content)) !!}</p>

        <a href="{{ route('public.articles.index') }}" class="btn btn-secondary mt-3">← Volver</a>
    </div>
@endsection
