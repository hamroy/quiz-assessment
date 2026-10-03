<?php

use App\Enums\QuizAttemptStatus;
use App\Models\QuizAttempt;
use App\Services\Assessment\QuizAttemptService;
use App\Services\Quiz\QuizService;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.public')] class extends Component
{
    public int $attemptId;

    public int $current = 0;

    public array $selected = [];

    public function mount(string $slug, QuizAttempt $attempt, QuizService $quizService): void
    {
        $quiz = $quizService->findPublishedBySlug($slug);

        if ($attempt->quiz_id !== $quiz->id || $attempt->status !== QuizAttemptStatus::InProgress->value) {
            abort(404);
        }

        $this->attemptId = $attempt->id;

        $this->selected = $attempt->answers()
            ->pluck('answer_option_id', 'question_id')
            ->mapWithKeys(fn ($optionId, $questionId) => [$questionId => (int) $optionId])
            ->all();
    }

    private function attempt(): QuizAttempt
    {
        return app(QuizAttemptService::class)->findById($this->attemptId);
    }

    private function questions(): Collection
    {
        return $this->attempt()->quiz->questions()
            ->with(['answerOptions' => fn ($q) => $q->orderBy('order')])
            ->orderBy('order')
            ->get();
    }

    public function selectOption(int $questionId, int $optionId, QuizAttemptService $service): void
    {
        $question = $this->questions()->firstWhere('id', $questionId);
        $option = $question->answerOptions->firstWhere('id', $optionId);

        $service->answer($this->attempt(), $question, $option);

        $this->selected[$questionId] = $optionId;
    }

    public function next(): void
    {
        if ($this->current < $this->questions()->count() - 1) {
            $this->current++;
        }
    }

    public function prev(): void
    {
        if ($this->current > 0) {
            $this->current--;
        }
    }

    public function submit(QuizAttemptService $service): void
    {
        $service->submit($this->attempt());

        $this->redirectRoute('quizzes.result', ['slug' => $this->attempt()->quiz->slug, 'attempt' => $this->attemptId], navigate: true);
    }

    public function with(): array
    {
        return [
            'question' => $this->questions()[$this->current] ?? null,
            'total' => $this->questions()->count(),
        ];
    }
}; ?>

<div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 sm:py-10 lg:px-8">
    @if ($question)
        <div class="mb-5 flex items-center justify-between">
            <p class="text-sm font-semibold text-gray-700">
                {{ __('Question') }} {{ $current + 1 }} {{ __('of') }} {{ $total }}
            </p>
            <span class="chip">{{ (int) round(($current + 1) / $total * 100) }}%</span>
        </div>

        <div class="mb-2 h-2 w-full overflow-hidden rounded-full bg-gray-200">
            <div class="h-2 rounded-full bg-brand-600 transition-all" style="width: {{ ($current + 1) / $total * 100 }}%"></div>
        </div>

        <div class="card mt-6 p-6 sm:p-8" wire:key="question-{{ $question->id }}">
            <h2 class="text-lg font-semibold text-gray-900">{{ $question->question }}</h2>

            <div class="mt-6 space-y-3">
                @foreach ($question->answerOptions as $option)
                    <label class="flex cursor-pointer items-center gap-3 rounded-xl border px-4 py-3.5 transition
                        @if (($selected[$question->id] ?? null) === $option->id) border-brand-600 bg-brand-50 @else border-gray-200 hover:bg-gray-50 @endif">
                        <input type="radio" name="question-{{ $question->id }}" value="{{ $option->id }}"
                            wire:click="selectOption({{ $question->id }}, {{ $option->id }})"
                            @checked(($selected[$question->id] ?? null) === $option->id)
                            class="h-4 w-4 border-gray-300 text-brand-600 focus:ring-brand-500">
                        <span class="text-sm text-gray-900">{{ $option->option_text }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Sticky bottom nav on mobile; inline controls on larger screens --}}
        <div class="mt-6 flex items-center justify-between gap-3 sm:mt-8">
            <button type="button" wire:click="prev" @disabled($current === 0) class="btn-secondary">
                {{ __('Previous') }}
            </button>

            @if ($current < $total - 1)
                <button type="button" wire:key="nav-next" wire:click="next" class="btn-primary">
                    {{ __('Next') }}
                </button>
            @else
                <button type="button" wire:key="nav-submit" wire:click="submit" wire:confirm="{{ __('Submit your assessment?') }}" class="btn-primary">
                    {{ __('Submit') }}
                </button>
            @endif
        </div>
    @else
        <div class="card p-8 text-center text-gray-500">
            {{ __('This quiz has no questions.') }}
        </div>
    @endif
</div>
