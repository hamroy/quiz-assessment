<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.public')] class extends Component {}; ?>

<div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <h1 class="text-2xl font-semibold">{{ config('app.name', 'Laravel') }}</h1>
            <p class="mt-4 text-gray-500">
                Published quizzes will appear here.
            </p>
        </div>
    </div>
</div>
