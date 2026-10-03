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

<div class="p-4 sm:p-6 lg:p-8">
    <div class="mb-4 flex items-center justify-between">
        <h2 class="text-lg font-semibold text-gray-900">{{ __('Edit Question') }}</h2>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.answer-options.create', ['quiz' => $quizId, 'question' => $questionId]) }}" wire:navigate class="btn-secondary text-xs">{{ __('Add Option') }}</a>
            <a href="{{ route('admin.questions.view', ['quiz' => $quizId, 'question' => $questionId]) }}" wire:navigate class="btn-secondary text-xs">{{ __('View') }}</a>
            <a href="{{ route('admin.questions.index', $quizId) }}" wire:navigate class="btn-secondary text-xs">{{ __('Back') }}</a>
        </div>
    </div>

    <div class="card p-6 sm:p-8">
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
                <a href="{{ route('admin.questions.index', $quizId) }}" wire:navigate class="btn-secondary">{{ __('Cancel') }}</a>
                <button type="submit" class="btn-primary">{{ __('Save Changes') }}</button>
            </div>
        </form>

        <div class="mt-8 border-t border-gray-200 pt-6">
            <h3 class="mb-4 text-base font-semibold text-gray-900">{{ __('Answer Options') }}</h3>
            <div class="space-y-2">
                @forelse ($options as $option)
                    <div class="flex items-center justify-between rounded-lg border px-4 py-3
                        @if ($option->is_correct) border-green-500 bg-green-50 @else border-gray-200 @endif">
                        <span class="text-sm @if ($option->is_correct) font-semibold text-green-700 @else text-gray-900 @endif">
                            {{ $option->option_text }}
                        </span>
                        <span class="flex items-center gap-3">
                            @if ($option->is_correct)
                                <span class="text-xs font-semibold text-green-700">{{ __('✓ Correct') }}</span>
                            @endif
                            <a href="{{ route('admin.answer-options.edit', ['quiz' => $quizId, 'question' => $questionId, 'answerOption' => $option->id]) }}" wire:navigate class="text-xs text-brand-600 hover:text-brand-700">{{ __('Edit') }}</a>
                            <button type="button" wire:click="deleteOption({{ $option->id }})" wire:confirm="Delete this option?" class="text-xs text-red-600 hover:text-red-700">{{ __('Delete') }}</button>
                        </span>
                    </div>
                @empty
                    <span class="text-sm text-gray-400">{{ __('No answer options yet.') }}</span>
                @endforelse
            </div>
        </div>
    </div>
</div>
