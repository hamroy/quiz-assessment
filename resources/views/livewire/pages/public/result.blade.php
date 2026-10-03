<?php

use App\Enums\QuizAttemptStatus;
use App\Models\QuizAttempt;
use App\Services\Assessment\EvaluationService;
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

    public function with(EvaluationService $service): array
    {
        return ['result' => $service->evaluate($this->attempt)];
    }
}; ?>

<div class="mx-auto max-w-3xl px-4 py-10 sm:px-6 sm:py-12 lg:px-8">
    <div class="card overflow-hidden">
        <div class="bg-gradient-to-b from-brand-50 to-white p-6 text-center sm:p-8">
            <h1 class="text-2xl font-semibold text-gray-900">{{ __('Assessment Result') }}</h1>
            <p class="mt-2 text-gray-600">{{ $attempt->quiz->title }}</p>

            <div class="mt-6 flex items-center justify-center">
                <div class="flex h-32 w-32 items-center justify-center rounded-full border-8 border-brand-100 bg-white">
                    <div class="text-center">
                        <p class="text-3xl font-bold text-gray-900">{{ $attempt->score }}<span class="text-lg text-gray-400">%</span></p>
                        <p class="mt-1 text-xs uppercase tracking-widest text-gray-500">{{ __('Your Score') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-6 sm:p-8">
            <section aria-labelledby="evaluation-summary">
                <h2 id="evaluation-summary" class="sr-only">{{ __('Answer Summary') }}</h2>
                <dl class="grid grid-cols-1 gap-3 sm:grid-cols-3 sm:text-center">
                    <div class="rounded-xl bg-green-50 p-4 text-green-800">
                        <dt class="text-xs font-semibold uppercase tracking-wide">{{ __('Correct') }}</dt>
                        <dd class="mt-1 text-2xl font-semibold">{{ $result->correct }}</dd>
                    </div>
                    <div class="rounded-xl bg-red-50 p-4 text-red-800">
                        <dt class="text-xs font-semibold uppercase tracking-wide">{{ __('Wrong') }}</dt>
                        <dd class="mt-1 text-2xl font-semibold">{{ $result->wrong }}</dd>
                    </div>
                    <div class="rounded-xl bg-gray-100 p-4 text-gray-700">
                        <dt class="text-xs font-semibold uppercase tracking-wide">{{ __('Unanswered') }}</dt>
                        <dd class="mt-1 text-2xl font-semibold">{{ $result->unanswered }}</dd>
                    </div>
                </dl>
            </section>

            <section class="mt-8 space-y-4" aria-labelledby="question-review">
                <h2 id="question-review" class="text-lg font-semibold text-gray-900">{{ __('Question Review') }}</h2>
                @foreach ($result->items as $index => $item)
                    <article class="rounded-xl border border-gray-200 p-4">
                        <div class="flex items-start justify-between gap-4">
                            <h3 class="font-medium text-gray-900">{{ $index + 1 }}. {{ $item->question }}</h3>
                            <span class="shrink-0 text-sm font-semibold {{ $item->answered ? ($item->isCorrect ? 'text-green-700' : 'text-red-700') : 'text-gray-600' }}">
                                @if (! $item->answered)
                                    {{ __('Unanswered') }}
                                @elseif ($item->isCorrect)
                                    {{ __('Correct') }}
                                @else
                                    {{ __('Incorrect') }}
                                @endif
                            </span>
                        </div>
                        <dl class="mt-3 space-y-1 text-sm">
                            <div>
                                <dt class="inline font-medium text-gray-600">{{ __('Your answer:') }}</dt>
                                <dd class="inline text-gray-900">{{ $item->selectedText ?? __('Not answered') }}</dd>
                            </div>
                            <div>
                                <dt class="inline font-medium text-gray-600">{{ __('Correct answer:') }}</dt>
                                <dd class="inline text-gray-900">{{ $item->correctText ?? __('Not available') }}</dd>
                            </div>
                            <div>
                                <dt class="inline font-medium text-gray-600">{{ __('Points:') }}</dt>
                                <dd class="inline text-gray-900">{{ $item->points }} / {{ $item->maxPoints }}</dd>
                            </div>
                        </dl>
                    </article>
                @endforeach
            </section>

            <div class="mt-8 border-t border-gray-100 pt-6 text-center">
                <a href="{{ route('home') }}" wire:navigate class="btn-primary sm:px-8 sm:py-3">
                    {{ __('Back to Assessments') }}
                </a>
            </div>
        </div>
    </div>
</div>
