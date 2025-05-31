<!DOCTYPE html>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>{{ config('app.name', 'WikiProyecto') }}</title>
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        </head>
        <body class="bg-gray-100 text-gray-900 antialiased">
        @include('layouts.navigation')

        <main class="py-6 px-4 max-w-7xl mx-auto">
            @yield('content')
        </main>
    </body>
</html>
