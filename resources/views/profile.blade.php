@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-4">{{ __('profile.title') }}</h1>

        <div class="mb-6">
            <p class="text-lg">{{ __('profile.welcome') }}, {{ $user->name }}</p>
            <p class="text-gray-600">{{ __('profile.total_posts') }}: {{ $postsCount }}</p>
            <p class="text-gray-600">{{ __('profile.total_comments') }}: {{ $commentsCount }}</p>
        </div>

        {{-- Mensaje de éxito --}}
        @if (session('status'))
            <div class="mb-4 text-green-600 font-semibold">
                {{ __('profile.updated') }}
            </div>
        @endif

        {{-- Formulario para actualizar el nombre --}}
        <div class="mb-6">
            <h2 class="text-xl font-semibold mb-2">{{ __('profile.update_name') }}</h2>

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PATCH')

                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">{{ __('profile.name') }}</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                           class="w-full border p-2 rounded mt-1" required>
                    @error('name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    {{ __('buttons.update_button') }}
                </button>
            </form>
        </div>

        <div class="mt-4">
            <a href="{{ route('lang.switch', 'es') }}" class="px-3 py-1 bg-gray-300 rounded mr-2">ES</a>
            <a href="{{ route('lang.switch', 'en') }}" class="px-3 py-1 bg-gray-300 rounded">EN</a>
        </div>
    </div>
@endsection
