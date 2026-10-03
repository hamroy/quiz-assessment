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
        if (! auth()->check()) {
            $this->redirectRoute('login', navigate: true);

            return;
        }

        $attempt = $service->start($this->quiz, auth()->user());

        $this->redirectRoute('quizzes.take', ['slug' => $this->quiz->slug, 'attempt' => $attempt->id], navigate: true);
    }
}; ?>

<div class="mx-auto max-w-3xl px-4 py-10 sm:px-6 sm:py-12 lg:px-8">
    <a href="{{ route('home') }}" wire:navigate class="inline-flex items-center gap-1 text-sm font-medium text-brand-600 hover:text-brand-700">
        &larr; {{ __('Back to Assessments') }}
    </a>

    <div class="card mt-6 p-6 sm:p-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">{{ $quiz->title }}</h1>
                <span class="chip mt-3">{{ $quiz->questions_count }} {{ __('Questions') }}</span>
            </div>
        </div>

        @if ($quiz->description)
            <p class="mt-4 text-gray-600">{{ $quiz->description }}</p>
        @endif

        <div class="mt-6 border-t border-gray-100 pt-6">
            <button type="button" wire:click="start" class="btn-primary w-full sm:w-auto sm:px-8 sm:py-3">
                {{ __('Start Assessment') }}
            </button>
        </div>
    </div>
</div>
