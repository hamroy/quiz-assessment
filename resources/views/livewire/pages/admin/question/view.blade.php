<?php

use App\Models\Question;
use App\Services\AnswerOption\AnswerOptionService;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    public Question $question;

    public function mount(Question $question): void
    {
        $this->question = $question->loadMissing('quiz');
    }

    public function with(): array
    {
        return [
            'options' => app(AnswerOptionService::class)->listForQuestion($this->question),
        ];
    }
}; ?>

<div>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="font-semibold text-xl">View Question</h2>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $question->quiz->title }}</p>
                        </div>
                        <a href="{{ route('admin.questions.index', $question->quiz_id) }}" wire:navigate
                            class="text-sm text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                            Back to Questions
                        </a>
                    </div>

                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Question</dt>
                            <dd class="mt-1 text-gray-900 dark:text-gray-100">{{ $question->question }}</dd>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Type</dt>
                                <dd class="mt-1 text-gray-900 dark:text-gray-100">{{ ucfirst(str_replace('_', ' ', $question->type)) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Points</dt>
                                <dd class="mt-1 text-gray-900 dark:text-gray-100">{{ $question->points }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Order</dt>
                                <dd class="mt-1 text-gray-900 dark:text-gray-100">{{ $question->order }}</dd>
                            </div>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Answer Options</dt>
                            <dd class="mt-2 space-y-2">
                                @forelse ($options as $option)
                                    <div class="flex items-center gap-2 rounded-md border px-3 py-2
                                        @if ($option->is_correct) border-green-500 bg-green-50 dark:bg-green-900/20 dark:border-green-500 @else border-gray-200 dark:border-gray-700 @endif">
                                        <span class="text-sm @if ($option->is_correct) font-semibold text-green-700 dark:text-green-400 @else text-gray-900 dark:text-gray-100 @endif">
                                            {{ $option->option_text }}
                                        </span>
                                        @if ($option->is_correct)
                                            <span class="text-xs font-semibold text-green-700 dark:text-green-400">✓ Correct</span>
                                        @endif
                                    </div>
                                @empty
                                    <span class="text-gray-400 dark:text-gray-500">No answer options yet.</span>
                                @endforelse
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
