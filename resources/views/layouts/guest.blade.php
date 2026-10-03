<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-b from-brand-50 to-gray-50">
            <div>
                <a href="{{ route('home') }}" wire:navigate class="flex items-center gap-2 text-lg font-semibold text-gray-900">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-600 text-base font-bold text-white">
                        {{ strtoupper(substr(config('app.name', 'Laravel'), 0, 1)) }}
                    </span>
                </a>
            </div>

            <div class="card w-full sm:max-w-md mt-6 px-6 py-8">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
