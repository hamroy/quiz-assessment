<?php

use App\Services\Assessment\QuizAttemptService;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.public')] class extends Component
{
    public function with(): array
    {
        return [
            'attempts' => app(QuizAttemptService::class)->listSubmittedForUser(auth()->id()),
        ];
    }
}; ?>

<div class="mx-auto max-w-3xl px-4 py-10 sm:px-6 sm:py-12 lg:px-8">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-900">{{ __('My Results') }}</h1>
        <a href="{{ route('home') }}" wire:navigate class="text-sm font-medium text-brand-600 hover:text-brand-700">
            &larr; {{ __('Back to Assessments') }}
        </a>
    </div>

    @if ($attempts->isEmpty())
        <div class="card p-8 text-center text-gray-500">
            {{ __('No results yet.') }}
        </div>
    @else
        <div class="space-y-4">
            @foreach ($attempts as $attempt)
                <a href="{{ route('quizzes.result', ['slug' => $attempt->quiz->slug, 'attempt' => $attempt->id]) }}" wire:navigate
                    class="card flex items-center justify-between gap-4 p-5 transition hover:shadow-md">
                    <div>
                        <h2 class="font-semibold text-gray-900">{{ $attempt->quiz->title }}</h2>
                        <p class="mt-1 text-sm text-gray-500">{{ $attempt->submitted_at?->format('Y-m-d H:i') }}</p>
                    </div>
                    <span class="text-2xl font-bold text-brand-600">{{ $attempt->score }}%</span>
                </a>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $attempts->links() }}
        </div>
    @endif
</div>
