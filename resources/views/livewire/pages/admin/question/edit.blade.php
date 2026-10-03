<?php

use App\Enums\QuestionType;
use App\Livewire\Forms\QuestionForm;
use App\Services\AnswerOption\AnswerOptionService;
use App\Services\Question\QuestionService;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    public int $quizId;

    public int $questionId;

    public QuestionForm $form;

    public function mount(int $quiz, int $question): void
    {
        $questionModel = app(QuestionService::class)->findById($question);

        $this->quizId = $quiz;
        $this->questionId = $questionModel->id;
        $this->form->question = $questionModel->question;
        $this->form->type = $questionModel->type;
        $this->form->points = $questionModel->points;
        $this->form->order = $questionModel->order;
    }

    public function with(): array
    {
        return [
            'options' => app(AnswerOptionService::class)->listForQuestion(
                app(QuestionService::class)->findById($this->questionId),
            ),
        ];
    }

    public function save(QuestionService $service): void
    {
        $service->update($service->findById($this->questionId), $this->form->validate());

        $this->redirectRoute('admin.questions.index', ['quiz' => $this->quizId], navigate: true);
    }

    public function deleteOption(int $optionId, AnswerOptionService $service): void
    {
        $service->delete($service->findById($optionId));
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
                            <a href="{{ route('admin.questions.view', ['quiz' => $quizId, 'question' => $questionId]) }}" wire:navigate
                                class="text-sm text-sky-600 hover:text-sky-900 dark:text-sky-400 dark:hover:text-sky-300">
                                View
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
                            <textarea id="question" wire:model="form.question" rows="4"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                            @error('form.question')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type</label>
                            <select id="type" wire:model="form.type"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach (QuestionType::cases() as $t)
                                    <option value="{{ $t->value }}">{{ ucfirst(str_replace('_', ' ', $t->value)) }}</option>
                                @endforeach
                            </select>
                            @error('form.type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="points" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Points</label>
                                <input id="points" type="number" wire:model="form.points" min="1"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('form.points')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="order" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Order</label>
                                <input id="order" type="number" wire:model="form.order" min="1"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('form.order')
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

                    <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="font-semibold text-lg mb-4">Answer Options</h3>
                        <div class="space-y-2">
                            @forelse ($options as $option)
                                <div class="flex items-center justify-between rounded-md border px-3 py-2
                                    @if ($option->is_correct) border-green-500 bg-green-50 dark:bg-green-900/20 dark:border-green-500 @else border-gray-200 dark:border-gray-700 @endif">
                                    <span class="text-sm @if ($option->is_correct) font-semibold text-green-700 dark:text-green-400 @else text-gray-900 dark:text-gray-100 @endif">
                                        {{ $option->option_text }}
                                    </span>
                                    <span class="flex items-center gap-3">
                                        @if ($option->is_correct)
                                            <span class="text-xs font-semibold text-green-700 dark:text-green-400">✓ Correct</span>
                                        @endif
                                        <a href="{{ route('admin.answer-options.edit', ['quiz' => $quizId, 'question' => $questionId, 'answerOption' => $option->id]) }}" wire:navigate class="text-xs text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">Edit</a>
                                        <button type="button" wire:click="deleteOption({{ $option->id }})" wire:confirm="Delete this option?" class="text-xs text-red-600 hover:text-red-900 dark:text-red-400">Delete</button>
                                    </span>
                                </div>
                            @empty
                                <span class="text-gray-400 dark:text-gray-500">No answer options yet.</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
