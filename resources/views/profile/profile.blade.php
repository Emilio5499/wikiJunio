@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto p-6 bg-white rounded shadow">

        <h1 class="text-2xl font-bold mb-6">{{ __('profile.profile_title') }}</h1>

        {{-- Botones de idioma --}}
        <div class="mb-4">
            <a href="{{ route('lang.switch', 'es') }}" class="px-3 py-1 bg-gray-300 rounded mr-2">ES</a>
            <a href="{{ route('lang.switch', 'en') }}" class="px-3 py-1 bg-gray-300 rounded">EN</a>
        </div>

        {{-- Información del usuario --}}
        <div class="mb-6">
            <p><strong>{{ __('profile.name') }}:</strong> {{ Auth::user()->name }}</p>
            <p><strong>{{ __('profile.email') }}:</strong> {{ Auth::user()->email }}</p>
            <p><strong>{{ __('profile.post_count') }}:</strong> {{ Auth::user()->articles()->count() }}</p>
            <p><strong>{{ __('profile.comment_count') }}:</strong> {{ Auth::user()->comments()->count() }}</p>
        </div>

        {{-- Formulario para actualizar nombre --}}
        <h2 class="text-xl font-semibold mb-4">{{ __('profile.update_name') }}</h2>

        <form method="POST" action="{{ route('profile.update') }}" class="space-y-4 mb-8">
            @csrf
            @method('PATCH')

            <div>
                <label for="name" class="block font-medium text-gray-700">{{ __('profile.new_name') }}</label>
                <input type="text" id="name" name="name" value="{{ old('name', Auth::user()->name) }}" required
                       class="w-full border rounded p-2 mt-1">
                @error('name')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                {{ __('buttons.save') }}
            </button>
        </form>

        {{-- Formulario para actualizar contraseña --}}
        <h2 class="text-xl font-semibold mb-4">{{ __('profile.update_password') }}</h2>

        <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
            @csrf
            @method('PATCH')

            <div>
                <label for="password" class="block font-medium text-gray-700">{{ __('profile.new_password') }}</label>
                <input type="password" id="password" name="password" required
                       class="w-full border rounded p-2 mt-1">
                @error('password')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block font-medium text-gray-700">{{ __('profile.confirm_password') }}</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required
                       class="w-full border rounded p-2 mt-1">
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                {{ __('buttons.update_password') }}
            </button>
        </form>
    </div>
@endsection
