@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Crear nuevo post</h1>

        @livewire('article-crud')
    </div>
@endsection
