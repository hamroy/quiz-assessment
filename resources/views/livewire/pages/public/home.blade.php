<?php

use App\Services\Quiz\QuizService;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.public')] class extends Component
{
    public function with(): array
    {
        return [
            'quizzes' => app(QuizService::class)->listPublished(),
        ];
    }
}; ?>

<div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-2xl font-semibold text-gray-900">{{ __('Assessments') }}</h1>
    </div>

    @if ($quizzes->isEmpty())
        <div class="rounded-lg bg-white p-8 text-center text-gray-500 shadow-sm">
            {{ __('No assessments available yet.') }}
        </div>
    @else
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($quizzes as $quiz)
                <a href="{{ route('quizzes.show', $quiz->slug) }}" wire:navigate class="flex flex-col rounded-lg bg-white p-6 shadow-sm transition hover:shadow-md">
                    <h2 class="text-lg font-semibold text-gray-900">{{ $quiz->title }}</h2>

                    <p class="mt-1 text-sm text-gray-500">
                        {{ $quiz->questions_count }} {{ __('Questions') }}
                    </p>

                    @if ($quiz->description)
                        <p class="mt-3 line-clamp-2 text-sm text-gray-600">{{ $quiz->description }}</p>
                    @endif

                    <span class="mt-4 inline-flex items-center justify-center rounded-md bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ __('Start Assessment') }}
                    </span>
                </a>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $quizzes->links() }}
        </div>
    @endif
</div>
