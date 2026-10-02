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

<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h2 class="font-semibold text-xl">Questions</h2>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $quiz->title }}</p>
                        </div>
                        <div class="flex items-center gap-4">
                            <a href="{{ route('admin.questions.create', $quiz) }}" wire:navigate
                                class="text-sm text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                Add Question
                            </a>
                            <a href="{{ route('admin.quizzes.index') }}" wire:navigate
                                class="text-sm text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                Back to Quizzes
                            </a>
                        </div>
                    </div>

                    @if ($deleteError)
                        <p class="mb-4 text-sm text-red-600 dark:text-red-400">{{ $deleteError }}</p>
                    @endif

                    @if ($questions->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400 py-8 text-center">
                            No questions yet.
                        </p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Question</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Type</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Points</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Order</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Options</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($questions as $question)
                                        <tr>
                                            <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">{{ $question->question }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ ucfirst(str_replace('_', ' ', $question->type)) }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $question->points }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $question->order }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $question->answer_options_count }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('admin.questions.edit', ['quiz' => $quiz, 'question' => $question]) }}" wire:navigate class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">Edit</a>
                                                <button type="button" wire:click="delete({{ $question->id }})" wire:confirm="Delete this question?" class="ms-3 text-red-600 hover:text-red-900 dark:text-red-400">Delete</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $questions->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
