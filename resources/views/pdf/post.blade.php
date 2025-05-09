<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
body { font-family: sans-serif; font-size: 14px; }
        h1 { font-size: 20px; }
        .meta { font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <h1>{{ $article->title }}</h1>
<p class="meta">
    Por: {{ $article->user->name}}<br>
</p>
<hr>
<p>{!! nl2br(e($article->content)) !!}</p>
</body>
</html>
