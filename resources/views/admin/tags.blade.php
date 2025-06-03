@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto py-10">
        <h1 class="text-2xl font-bold mb-4">Etiquetas</h1>
        @livewire('tag-crud')
    </div>
@endsection
