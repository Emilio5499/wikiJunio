<div class="container">
    <h1>{{ $article->title }}</h1>

    <p class="text-muted">
        Por {{ $article->user->name ?? 'Anónimo' }} — {{ $article->created_at->diffForHumans() }}
    </p>

    @if ($article->tags->count())
        <p>
            @foreach ($article->tags as $tag)
                <span class="badge bg-info text-dark">{{ $tag->name }}</span>
            @endforeach
        </p>
    @endif

    <hr>
    <div>{!! nl2br(e($article->content)) !!}</div>

    @if ($article->collaborators->count())
        <hr>
        <h5>Colaboradores</h5>
        <ul>
            @foreach ($article->collaborators as $user)
                <li>{{ $user->name }}</li>
            @endforeach
        </ul>
    @endif

    <a href="{{ route('articles.downloadPdf', $article) }}" class="btn btn-primary mt-3">
        Descargar
    </a>

    <a href="{{ route('public.articles.index') }}" class="btn btn-outline-secondary mt-4">← Volver</a>
</div>
