<?php

use App\Enums\QuizStatus;
use App\Livewire\Forms\QuizForm;
use App\Services\Quiz\QuizService;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    public int $quizId;

    public QuizForm $form;

    public function mount(int $quiz): void
    {
        $quiz = app(QuizService::class)->findById($quiz);

        $this->quizId = $quiz->id;
        $this->form->quizId = $quiz->id;
        $this->form->title = $quiz->title;
        $this->form->description = $quiz->description ?? '';
        $this->form->status = $quiz->status;
    }

    public function save(QuizService $service): void
    {
        $service->update($service->findById($this->quizId), $this->form->validate());

        $this->redirectRoute('admin.quizzes.index', navigate: true);
    }
}; ?>

<div class="p-4 sm:p-6 lg:p-8">
    <div class="mb-4">
        <a href="{{ route('admin.quizzes.index') }}" wire:navigate class="inline-flex items-center gap-1 text-sm font-medium text-brand-600 hover:text-brand-700">
            &larr; {{ __('Back to Quizzes') }}
        </a>
    </div>

    <div class="card p-6 sm:p-8">
        <h2 class="mb-6 text-lg font-semibold text-gray-900">{{ __('Edit Quiz') }}</h2>

        <form wire:submit="save" class="space-y-6">
            <div>
                <x-input-label for="title" :value="__('Title')" />
                <x-text-input wire:model="form.title" id="title" class="mt-1 block w-full" type="text" name="title" required autofocus />
                <x-input-error :messages="$errors->get('form.title')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="description" :value="__('Description')" />
                <textarea wire:model="form.description" id="description" name="description" rows="4"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500"></textarea>
                <x-input-error :messages="$errors->get('form.description')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="status" :value="__('Status')" />
                <select wire:model="form.status" id="status" name="status"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
                    @foreach (QuizStatus::cases() as $case)
                        <option value="{{ $case->value }}">{{ ucfirst($case->value) }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('form.status')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-gray-100 pt-6">
                <a href="{{ route('admin.quizzes.index') }}" wire:navigate class="btn-secondary">
                    {{ __('Cancel') }}
                </a>
                <button type="submit" class="btn-primary">
                    {{ __('Save') }}
                </button>
            </div>
        </form>
    </div>
</div>
