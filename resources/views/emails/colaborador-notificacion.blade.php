
<h1>¡Hola!</h1>
<p>Ahora eres un colaborador en:</p>

<p><strong>Título:</strong> {{ $article->title }}</p>
<p><strong>Autor:</strong> {{ $article->user->name }}</p>
<p><strong>Fecha:</strong> {{ $article->created_at->format('d/m/Y') }}</p>
