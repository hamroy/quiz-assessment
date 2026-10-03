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
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-50" x-data="{ sidebarOpen: false }">
            <!-- Mobile sidebar backdrop -->
            <div x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak
                 class="fixed inset-0 z-40 bg-black/50 lg:hidden"></div>

            <!-- Sidebar -->
            <aside class="fixed top-0 left-0 z-50 h-screen w-64 -translate-x-full transition-transform duration-300 lg:translate-x-0"
                   :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
                   :aria-expanded="sidebarOpen">
                <livewire:layout.navigation />
            </aside>

            <!-- Main content -->
            <div class="lg:pl-64">
                <!-- Top bar (mobile hamburger + header) -->
                <header class="sticky top-0 z-30 border-b border-gray-200 bg-white/80 backdrop-blur">
                    <div class="flex h-16 items-center gap-4 px-4 sm:px-6 lg:px-8">
                        <button type="button" @click="sidebarOpen = !sidebarOpen"
                                class="rounded-lg p-2 text-gray-600 hover:bg-gray-100 lg:hidden" aria-label="Toggle sidebar">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                        </button>

                        @if (isset($header))
                            <div class="flex items-center gap-3">
                                <h1 class="text-lg font-semibold text-gray-900">
                                    {{ $header }}
                                </h1>
                            </div>
                        @endif
                    </div>
                </header>

                <!-- Page Content -->
                <main>
                    {{ $slot }}
                </main>
            </div>
        </div>

        <x-confirmation-modal />
    </body>
</html>
