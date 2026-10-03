<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 font-sans text-gray-900 antialiased">
    <div class="flex min-h-screen flex-col">
        <header class="border-b border-gray-200 bg-white">
            <nav class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8" aria-label="Main navigation">
                <a href="{{ route('home') }}" wire:navigate class="text-lg font-semibold text-gray-900">
                    {{ config('app.name', 'Laravel') }}
                </a>
                @auth
                    <a href="{{ route('dashboard') }}" wire:navigate class="rounded-md px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                        {{ __('Dashboard') }}
                    </a>
                @else
                    <a href="{{ route('login') }}" wire:navigate class="rounded-md px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                        {{ __('Log in') }}
                    </a>
                @endauth
            </nav>
        </header>

        <main class="flex-1">
            {{ $slot }}
        </main>

        <footer class="border-t border-gray-200 bg-white">
            <div class="mx-auto max-w-7xl px-4 py-6 text-sm text-gray-500 sm:px-6 lg:px-8">
                &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}
            </div>
        </footer>
    </div>
</body>
</html>
