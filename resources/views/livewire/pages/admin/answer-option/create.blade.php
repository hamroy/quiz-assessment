<?php

use App\Livewire\Forms\AnswerOptionForm;
use App\Services\AnswerOption\AnswerOptionService;
use App\Services\Question\QuestionService;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    public int $quizId;

    public int $questionId;

    public AnswerOptionForm $form;

    public function mount(int $quiz, int $question): void
    {
        $questionModel = app(QuestionService::class)->findById($question);

        $this->quizId = $quiz;
        $this->questionId = $questionModel->id;
        $this->form->order = app(AnswerOptionService::class)->nextOrder($questionModel);
    }

    public function save(AnswerOptionService $service): void
    {
        $question = app(QuestionService::class)->findById($this->questionId);
        $service->create($question, $this->form->validate());

        $this->redirectRoute('admin.questions.edit', ['quiz' => $this->quizId, 'question' => $this->questionId], navigate: true);
    }
}; ?>

<div>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="font-semibold text-xl">Add Answer Option</h2>
                        <a href="{{ route('admin.questions.edit', ['quiz' => $quizId, 'question' => $questionId]) }}" wire:navigate
                            class="text-sm text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                            Back to Question
                        </a>
                    </div>

                    <form wire:submit="save" class="space-y-6">
                        <div>
                            <label for="option_text" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Option Text</label>
                            <input id="option_text" type="text" wire:model="form.option_text"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('form.option_text')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center">
                            <input id="is_correct" type="checkbox" wire:model="form.is_correct"
                                class="h-4 w-4 rounded border-gray-300 dark:border-gray-700 text-indigo-600 focus:ring-indigo-500">
                            <label for="is_correct" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Correct Answer</label>
                        </div>

                        <div>
                            <label for="order" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Order</label>
                            <input id="order" type="number" wire:model="form.order" min="1"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('form.order')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('admin.questions.edit', ['quiz' => $quizId, 'question' => $questionId]) }}" wire:navigate
                                class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                                Cancel
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                Save Option
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
