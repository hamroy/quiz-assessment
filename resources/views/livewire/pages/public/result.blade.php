<?php

use App\Enums\QuizAttemptStatus;
use App\Models\QuizAttempt;
use App\Services\Quiz\QuizService;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.public')] class extends Component
{
    public QuizAttempt $attempt;

    public function mount(string $slug, QuizAttempt $attempt, QuizService $quizService): void
    {
        $quiz = $quizService->findPublishedBySlug($slug);

        if ($attempt->quiz_id !== $quiz->id || $attempt->status !== QuizAttemptStatus::Submitted->value) {
            abort(404);
        }

        $this->attempt = $attempt->load('quiz');
    }
}; ?>

<div class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8">
    <div class="rounded-lg bg-white p-6 shadow-sm sm:p-8">
        <h1 class="text-2xl font-semibold text-gray-900">{{ __('Assessment Result') }}</h1>

        <p class="mt-2 text-gray-600">{{ $attempt->quiz->title }}</p>

        <div class="mt-8 flex items-center justify-center">
            <div class="text-center">
                <p class="text-sm uppercase tracking-widest text-gray-500">{{ __('Your Score') }}</p>
                <p class="mt-2 text-5xl font-bold text-gray-900">{{ $attempt->score }}<span class="text-2xl text-gray-400">%</span></p>
            </div>
        </div>

        <div class="mt-8 border-t border-gray-100 pt-6 text-center">
            <a href="{{ route('home') }}" wire:navigate
                class="inline-flex items-center justify-center rounded-md bg-gray-800 px-6 py-3 text-sm font-semibold uppercase tracking-widest text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Back to Assessments') }}
            </a>
        </div>
    </div>
</div>
