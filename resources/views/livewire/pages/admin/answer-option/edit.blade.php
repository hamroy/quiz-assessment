<?php

use App\Livewire\Forms\AnswerOptionForm;
use App\Services\AnswerOption\AnswerOptionService;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    public int $quizId;
    public int $questionId;
    public int $optionId;

    public AnswerOptionForm $form;

    public function mount(int $quiz, int $question, int $answerOption): void
    {
        $option = app(AnswerOptionService::class)->findById($answerOption);

        $this->quizId = $quiz;
        $this->questionId = $question;
        $this->optionId = $option->id;
        $this->form->option_text = $option->option_text;
        $this->form->is_correct = (bool) $option->is_correct;
        $this->form->order = $option->order;
    }

    public function save(AnswerOptionService $service): void
    {
        $option = $service->findById($this->optionId);
        $service->update($option, $this->form->validate());

        $this->redirectRoute('admin.questions.edit', ['quiz' => $this->quizId, 'question' => $this->questionId], navigate: true);
    }
}; ?>

<div class="p-4 sm:p-6 lg:p-8">
    <div class="mb-4">
        <a href="{{ route('admin.questions.edit', ['quiz' => $quizId, 'question' => $questionId]) }}" wire:navigate class="inline-flex items-center gap-1 text-sm font-medium text-brand-600 hover:text-brand-700">
            &larr; {{ __('Back to Question') }}
        </a>
    </div>

    <div class="card p-6 sm:p-8">
        <h2 class="mb-6 text-lg font-semibold text-gray-900">{{ __('Edit Answer Option') }}</h2>

        <form wire:submit="save" class="space-y-6">
            <div>
                <x-input-label for="option_text" :value="__('Option Text')" />
                <input id="option_text" type="text" wire:model="form.option_text"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
                @error('form.option_text')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center">
                <input id="is_correct" type="checkbox" wire:model="form.is_correct"
                    class="h-4 w-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                <label for="is_correct" class="ml-2 block text-sm text-gray-700">{{ __('Correct Answer') }}</label>
            </div>

            <div>
                <x-input-label for="order" :value="__('Order')" />
                <input id="order" type="number" wire:model="form.order" min="1"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
                @error('form.order')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-gray-100 pt-6">
                <a href="{{ route('admin.questions.edit', ['quiz' => $quizId, 'question' => $questionId]) }}" wire:navigate class="btn-secondary">{{ __('Cancel') }}</a>
                <button type="submit" class="btn-primary">{{ __('Save Changes') }}</button>
            </div>
        </form>
    </div>
</div>
