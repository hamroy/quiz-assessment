<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 font-sans text-gray-900 antialiased">
    <div class="flex min-h-screen flex-col">
        <header class="sticky top-0 z-40 border-b border-gray-200 bg-white/80 backdrop-blur">
            <nav class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8" aria-label="Main navigation" x-data="{ open: false }">
                <a href="{{ route('home') }}" wire:navigate class="flex items-center gap-2 text-lg font-semibold text-gray-900">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-brand-600 text-sm font-bold text-white">
                        {{ strtoupper(substr(config('app.name', 'Laravel'), 0, 1)) }}
                    </span>
                    <span>{{ config('app.name', 'Laravel') }}</span>
                </a>

                {{-- Mobile nav (links + auth actions) --}}
                <div class="sm:hidden">
                    <button type="button" x-on:click="open = !open" class="inline-flex items-center justify-center rounded-md p-2 text-gray-700 hover:bg-gray-100" aria-label="Toggle navigation">
                        <svg x-show="!open" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" /></svg>
                        <svg x-show="open" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                {{-- Desktop nav --}}
                <div class="hidden items-center gap-3 sm:flex">
                    @auth
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('dashboard') }}" wire:navigate class="rounded-md px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                {{ __('Dashboard') }}
                            </a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="rounded-md px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                {{ __('Log Out') }}
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" wire:navigate class="rounded-md px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                            {{ __('Log in') }}
                        </a>
                    @endauth
                </div>
            </nav>

            {{-- Mobile menu panel --}}
            <div class="sm:hidden" x-show="open" x-cloak x-transition>
                <div class="space-y-1 border-t border-gray-100 bg-white px-4 pb-3 pt-2">
                    @auth
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('dashboard') }}" wire:navigate class="block rounded-md px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                {{ __('Dashboard') }}
                            </a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full rounded-md px-3 py-2 text-left text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                {{ __('Log Out') }}
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" wire:navigate class="block rounded-md px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                            {{ __('Log in') }}
                        </a>
                    @endauth
                </div>
            </div>
        </header>

        <main class="flex-1">{{ $slot }}</main>

        <footer class="border-t border-gray-200 bg-white">
            <div class="mx-auto flex max-w-7xl flex-col gap-2 px-4 py-6 text-sm text-gray-500 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}</p>
                <p>{{ __('Built for self-understanding.') }}</p>
            </div>
        </footer>
    </div>
</body>
</html>
