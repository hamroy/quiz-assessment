<?php

use App\Enums\QuestionType;
use App\Services\Question\QuestionService;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    public int $quizId;

    public int $questionId;

    public string $question = '';
    public string $type = '';
    public int $points = 1;
    public int $order = 1;

    public function mount(int $quiz, int $question): void
    {
        $questionModel = app(QuestionService::class)->findById($question);

        $this->quizId = $quiz;
        $this->questionId = $questionModel->id;
        $this->question = $questionModel->question;
        $this->type = $questionModel->type;
        $this->points = $questionModel->points;
        $this->order = $questionModel->order;
    }

    public function save(QuestionService $service): void
    {
        $validated = $this->validate([
            'question' => ['required', 'string'],
            'type' => ['required', Rule::enum(QuestionType::class)],
            'points' => ['required', 'integer', 'min:1'],
            'order' => ['required', 'integer', 'min:1'],
        ]);

        $service->update($service->findById($this->questionId), $validated);

        $this->redirectRoute('admin.questions.index', ['quiz' => $this->quizId], navigate: true);
    }
}; ?>

<div>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="font-semibold text-xl">Edit Question</h2>
                        </div>
                        <div class="flex items-center gap-4">
                            <a href="{{ route('admin.answer-options.create', ['quiz' => $quizId, 'question' => $questionId]) }}" wire:navigate
                                class="text-sm text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                Add Answer Option
                            </a>
                            <a href="{{ route('admin.questions.index', $quizId) }}" wire:navigate
                                class="text-sm text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                Back to Questions
                            </a>
                        </div>
                    </div>

                    <form wire:submit="save" class="space-y-6">
                        <div>
                            <label for="question" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Question</label>
                            <textarea id="question" wire:model="question" rows="4"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                            @error('question')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type</label>
                            <select id="type" wire:model="type"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach (QuestionType::cases() as $t)
                                    <option value="{{ $t->value }}">{{ ucfirst(str_replace('_', ' ', $t->value)) }}</option>
                                @endforeach
                            </select>
                            @error('type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="points" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Points</label>
                                <input id="points" type="number" wire:model="points" min="1"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('points')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="order" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Order</label>
                                <input id="order" type="number" wire:model="order" min="1"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('order')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('admin.questions.index', $quizId) }}" wire:navigate
                                class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                                Cancel
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
