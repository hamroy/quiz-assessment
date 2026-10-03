<?php

use App\Models\Quiz;
use App\Services\Assessment\QuizAttemptService;
use App\Services\Quiz\QuizService;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.public')] class extends Component
{
    public Quiz $quiz;

    public function mount(string $slug, QuizService $service): void
    {
        $this->quiz = $service->findPublishedBySlug($slug)->loadCount('questions');
    }

    public function start(QuizAttemptService $service): void
    {
        $attempt = $service->start($this->quiz, auth()->user());

        $this->redirectRoute('quizzes.take', ['slug' => $this->quiz->slug, 'attempt' => $attempt->id], navigate: true);
    }
}; ?>

<div class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8">
    <a href="{{ route('home') }}" wire:navigate class="text-sm text-indigo-600 hover:text-indigo-900">
        &larr; {{ __('Back to Assessments') }}
    </a>

    <div class="mt-6 rounded-lg bg-white p-6 shadow-sm sm:p-8">
        <h1 class="text-2xl font-semibold text-gray-900">{{ $quiz->title }}</h1>

        <p class="mt-2 text-sm text-gray-500">
            {{ $quiz->questions_count }} {{ __('Questions') }}
        </p>

        @if ($quiz->description)
            <p class="mt-4 text-gray-600">{{ $quiz->description }}</p>
        @endif

        <div class="mt-6 border-t border-gray-100 pt-6">
            <button type="button" wire:click="start"
                class="inline-flex items-center justify-center rounded-md bg-gray-800 px-6 py-3 text-sm font-semibold uppercase tracking-widest text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Start Assessment') }}
            </button>
        </div>
    </div>
</div>
