<?php
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $article->title }}</title>
<style>
    body {
        font-family: DejaVu Sans, sans-serif;
        margin: 40px;
        position: relative;
    }
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .title {
        font-size: 24px;
        font-weight: bold;
    }
    .author {
        font-size: 14px;
        margin-top: 10px;
        color: #555;
    }
    .content {
        margin-top: 30px;
        font-size: 16px;
        line-height: 1.6;
    }
    .corner-image {
        position: absolute;
        top: 0;
        right: 0;
        width: 80px;
    }
</style>
</head>
<body>
<img src="{{ public_path('images/logo.png') }}" class="corner-image" alt="Logo">

<div class="header">
    <div class="title">{{ $article->title }}</div>
</div>

<div class="author">
    Escrito por: {{ $article->user->name }}<br>
    Publicado: {{ $article->published_at?->format('d/m/Y') ?? 'No publicado' }}
</div>

<div class="content">
    {!! nl2br(e($article->content)) !!}
</div>
</body>
</html>
