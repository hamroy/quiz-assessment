<?php

use App\Models\Quiz;
use App\Services\Question\QuestionService;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    public Quiz $quiz;

    public string $deleteError = '';

    public function mount(Quiz $quiz): void
    {
        $this->quiz = $quiz;
    }

    public function with(): array
    {
        return [
            'questions' => app(QuestionService::class)->listForQuiz($this->quiz),
        ];
    }

    public function delete(int $questionId, QuestionService $service): void
    {
        $service->delete($service->findById($questionId));
        $this->deleteError = '';
    }
}; ?>

<div class="p-4 sm:p-6 lg:p-8">
    <div class="mb-4 flex items-center justify-between">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">{{ __('Questions') }}</h2>
            <p class="mt-1 text-sm text-gray-500">{{ $quiz->title }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.questions.create', $quiz) }}" wire:navigate class="btn-primary">
                {{ __('Add Question') }}
            </a>
            <a href="{{ route('admin.quizzes.index') }}" wire:navigate class="btn-secondary">
                {{ __('Back to Quizzes') }}
            </a>
        </div>
    </div>

    @if ($deleteError)
        <p class="mb-4 text-sm text-red-600">{{ $deleteError }}</p>
    @endif

    @if ($questions->isEmpty())
        <div class="card p-8 text-center text-gray-500">
            {{ __('No questions yet.') }}
        </div>
    @else
        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Question') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Type') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Points') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Order') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Options') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($questions as $question)
                            <tr>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $question->question }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ ucfirst(str_replace('_', ' ', $question->type)) }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $question->points }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $question->order }}</td>
                                <td class="px-4 py-3 text-sm text-gray-500">
                                    @forelse ($question->answerOptions as $option)
                                        <div class="@if ($option->is_correct) font-semibold text-green-600 @endif">
                                            {{ $option->option_text }}
                                            @if ($option->is_correct)
                                                <span class="text-xs">{{ __('(correct)') }}</span>
                                            @endif
                                        </div>
                                    @empty
                                        <span class="text-gray-400">{{ __('No options') }}</span>
                                    @endforelse
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('admin.questions.view', ['quiz' => $quiz, 'question' => $question]) }}" wire:navigate class="text-sky-600 hover:text-sky-700">{{ __('View') }}</a>
                                        <a href="{{ route('admin.questions.edit', ['quiz' => $quiz, 'question' => $question]) }}" wire:navigate class="text-brand-600 hover:text-brand-700">{{ __('Edit') }}</a>
                                        <button type="button" wire:click="delete({{ $question->id }})" wire:confirm="Delete this question?" class="text-red-600 hover:text-red-700">{{ __('Delete') }}</button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="border-t border-gray-100 px-4 py-3">
                {{ $questions->links() }}
            </div>
        </div>
    @endif
</div>
