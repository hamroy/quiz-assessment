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

<div class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8">
    @if ($question)
        <div class="mb-6 flex items-center justify-between">
            <p class="text-sm font-medium text-gray-600">
                {{ __('Question') }} {{ $current + 1 }} {{ __('of') }} {{ $total }}
            </p>
        </div>

        <div class="mb-2 h-2 w-full overflow-hidden rounded-full bg-gray-200">
            <div class="h-2 rounded-full bg-indigo-600 transition-all" style="width: {{ ($current + 1) / $total * 100 }}%"></div>
        </div>

        <div class="mt-6 rounded-lg bg-white p-6 shadow-sm sm:p-8">
            <h2 class="text-lg font-semibold text-gray-900">{{ $question->question }}</h2>

            <div class="mt-6 space-y-3">
                @foreach ($question->answerOptions as $option)
                    <label class="flex cursor-pointer items-center gap-3 rounded-md border px-4 py-3
                        @if (($selected[$question->id] ?? null) === $option->id) border-indigo-600 bg-indigo-50 @else border-gray-200 hover:bg-gray-50 @endif">
                        <input type="radio" name="question-{{ $question->id }}" value="{{ $option->id }}"
                            wire:click="selectOption({{ $question->id }}, {{ $option->id }})"
                            @checked(($selected[$question->id] ?? null) === $option->id)
                            class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="text-sm text-gray-900">{{ $option->option_text }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="mt-6 flex items-center justify-between">
            <button type="button" wire:click="prev"
                @disabled($current === 0)
                class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50">
                {{ __('Previous') }}
            </button>

            @if ($current < $total - 1)
                <button type="button" wire:click="next"
                    class="inline-flex items-center rounded-md bg-gray-800 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                    {{ __('Next') }}
                </button>
            @else
                <button type="button" wire:click="submit" wire:confirm="{{ __('Submit your assessment?') }}"
                    class="inline-flex items-center rounded-md bg-gray-800 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                    {{ __('Submit') }}
                </button>
            @endif
        </div>
    @else
        <div class="rounded-lg bg-white p-8 text-center text-gray-500 shadow-sm">
            {{ __('This quiz has no questions.') }}
        </div>
    @endif
</div>
