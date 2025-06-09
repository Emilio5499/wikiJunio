@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-4">{{ __('Profile') }}</h1>

        <div class="mb-6">
            <p class="text-lg">{{ __('Welcome') }}, {{ $user->name }}</p>
            <p class="text-gray-600">{{ __('Total Posts') }}: {{ $postsCount }}</p>
            <p class="text-gray-600">{{ __('Total Comments') }}: {{ $commentsCount }}</p>
        </div>

        @if (session('status'))
            <div class="mb-4 text-green-600 font-semibold">
                {{ __(session('status')) }}
            </div>
        @endif

        <div class="mb-6">
            <h2 class="text-xl font-semibold mb-2">{{ __('Update name') }}</h2>

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PATCH')

                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">{{ __('Name') }}</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                           class="w-full border p-2 rounded mt-1" required>
                    @error('name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    {{ __('Update Name') }}
                </button>
            </form>
        </div>

        <div class="mb-6">
            <h2 class="text-xl font-semibold mb-2">{{ __('Update Password') }}</h2>

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PATCH')

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">{{ __('New Password') }}</label>
                    <input type="password" id="password" name="password" class="w-full border p-2 rounded mt-1">
                    @error('password')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">{{ __('Confirm Password') }}</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="w-full border p-2 rounded mt-1">
                </div>

                <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    {{ __('Update Password') }}
                </button>
            </form>
        </div>

        <div class="mt-4">
            <a href="{{ route('lang.switch', 'es') }}" class="px-3 py-1 bg-gray-300 rounded mr-2">ES</a>
            <a href="{{ route('lang.switch', 'en') }}" class="px-3 py-1 bg-gray-300 rounded">EN</a>
        </div>
    </div>
@endsection
