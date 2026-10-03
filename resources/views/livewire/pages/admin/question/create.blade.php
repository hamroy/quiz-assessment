<?php

use App\Enums\QuestionType;
use App\Livewire\Forms\QuestionForm;
use App\Models\Quiz;
use App\Services\Question\QuestionService;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    public Quiz $quiz;

    public QuestionForm $form;

    public function mount(Quiz $quiz): void
    {
        $this->quiz = $quiz;
        $this->form->order = app(QuestionService::class)->nextOrder($quiz);
    }

    public function save(QuestionService $service): void
    {
        $service->create($this->quiz, $this->form->validate());

        $this->redirectRoute('admin.questions.index', ['quiz' => $this->quiz->id], navigate: true);
    }
}; ?>

<div class="p-4 sm:p-6 lg:p-8">
    <div class="mb-4">
        <a href="{{ route('admin.questions.index', $quiz) }}" wire:navigate class="inline-flex items-center gap-1 text-sm font-medium text-brand-600 hover:text-brand-700">
            &larr; {{ __('Back to Questions') }}
        </a>
    </div>

    <div class="card p-6 sm:p-8">
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-900">{{ __('Add Question') }}</h2>
            <p class="mt-1 text-sm text-gray-500">{{ $quiz->title }}</p>
        </div>

        <form wire:submit="save" class="space-y-6">
            <div>
                <x-input-label for="question" :value="__('Question')" />
                <textarea id="question" wire:model="form.question" rows="4"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500"></textarea>
                @error('form.question')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <x-input-label for="type" :value="__('Type')" />
                <select id="type" wire:model="form.type"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
                    @foreach (QuestionType::cases() as $t)
                        <option value="{{ $t->value }}">{{ ucfirst(str_replace('_', ' ', $t->value)) }}</option>
                    @endforeach
                </select>
                @error('form.type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <x-input-label for="points" :value="__('Points')" />
                    <input id="points" type="number" wire:model="form.points" min="1"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
                    @error('form.points')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <x-input-label for="order" :value="__('Order')" />
                    <input id="order" type="number" wire:model="form.order" min="1"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
                    @error('form.order')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-gray-100 pt-6">
                <a href="{{ route('admin.questions.index', $quiz) }}" wire:navigate class="btn-secondary">{{ __('Cancel') }}</a>
                <button type="submit" class="btn-primary">{{ __('Save Question') }}</button>
            </div>
        </form>
    </div>
</div>
