@component('mail::message')
    #{{ $user->name }}

    Eres un colaborador en:

    **{{ $article->title }}**

    @component('mail::button', ['url' => url("/articles/{$article->id}")])
       Articulo
    @endcomponent
@endcomponent
