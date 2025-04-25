<?php

@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto py-10">
        <h1 class="text-2xl font-bold mb-4">Posts</h1>
        @livewire('article-crud')
    </div>
@endsection
