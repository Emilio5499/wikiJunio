@extends('layouts.app')

@section('content')
    <div class="max-w-md mx-auto bg-white p-6 rounded shadow">
        <h1 class="text-2xl font-bold mb-6 text-center">{{ __('auth.login_title') }}</h1>

        <div class="mb-4 text-center">
            <a href="{{ route('lang.switch', 'es') }}" class="px-3 py-1 bg-gray-300 rounded mr-2">ES</a>
            <a href="{{ route('lang.switch', 'en') }}" class="px-3 py-1 bg-gray-300 rounded">EN</a>
        </div>

        @if (session('status'))
            <div class="mb-4 text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">{{ __('auth.email') }}</label>
                <input id="email" name="email" type="email" required autofocus
                       class="w-full border rounded p-2 mt-1"
                       value="{{ old('email') }}">
                @error('email')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">{{ __('auth.password') }}</label>
                <input id="password" name="password" type="password" required
                       class="w-full border rounded p-2 mt-1">
                @error('password')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Remember Me --}}
            <div class="flex items-center mb-4">
                <input id="remember" name="remember" type="checkbox" class="mr-2">
                <label for="remember" class="text-sm text-gray-700">{{ __('auth.remember') }}</label>
            </div>

            {{-- Botones --}}
            <div class="flex justify-between items-center">
                <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    {{ __('auth.login_button') }}
                </button>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                       class="text-sm text-blue-600 hover:underline">
                        {{ __('buttons.forgot_password') }}
                    </a>
                @endif
            </div>
        </form>
    </div>
@endsection
