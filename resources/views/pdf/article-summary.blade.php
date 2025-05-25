<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .titulo { font-weight: bold; font-size: 16px; margin-bottom: 10px; }
        .article { margin-bottom: 20px; border-bottom: 1px solid #ccc; padding-bottom: 10px; }
    </style>
</head>
<body>
<h1>Resumen de posts</h1>

@foreach ($articles as $article)
    <div class="article">
        <div class="titulo">{{ $article->title }}</div>
        <p><strong>Autor:</strong> {{ $article->user->name }}</p>
        <p><strong>Categoría:</strong> {{ $article->category->name ?? 'Sin categoría' }}</p>
        <p><strong>Publicado:</strong> {{ $article->created_at->format('d/m/Y') }}</p>
        <p>{{ \Illuminate\Support\Str::limit($article->content, 150) }}</p>
    </div>
@endforeach
</body>
</html>
