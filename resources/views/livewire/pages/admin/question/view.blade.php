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

<div class="p-4 sm:p-6 lg:p-8">
    <div class="mb-4 flex items-center justify-between">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">{{ __('View Question') }}</h2>
            <p class="mt-1 text-sm text-gray-500">{{ $question->quiz->title }}</p>
        </div>
        <a href="{{ route('admin.questions.index', $question->quiz_id) }}" wire:navigate class="btn-secondary">
            {{ __('Back to Questions') }}
        </a>
    </div>

    <div class="card p-6 sm:p-8">
        <dl class="space-y-5">
            <div>
                <dt class="text-sm font-medium text-gray-500">{{ __('Question') }}</dt>
                <dd class="mt-1 text-gray-900">{{ $question->question }}</dd>
            </div>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div>
                    <dt class="text-sm font-medium text-gray-500">{{ __('Type') }}</dt>
                    <dd class="mt-1 text-gray-900">{{ ucfirst(str_replace('_', ' ', $question->type)) }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">{{ __('Points') }}</dt>
                    <dd class="mt-1 text-gray-900">{{ $question->points }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">{{ __('Order') }}</dt>
                    <dd class="mt-1 text-gray-900">{{ $question->order }}</dd>
                </div>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">{{ __('Answer Options') }}</dt>
                <dd class="mt-2 space-y-2">
                    @forelse ($options as $option)
                        <div class="flex items-center gap-2 rounded-lg border px-4 py-3
                            @if ($option->is_correct) border-green-500 bg-green-50 @else border-gray-200 @endif">
                            <span class="text-sm @if ($option->is_correct) font-semibold text-green-700 @else text-gray-900 @endif">
                                {{ $option->option_text }}
                            </span>
                            @if ($option->is_correct)
                                <span class="text-xs font-semibold text-green-700">{{ __('✓ Correct') }}</span>
                            @endif
                        </div>
                    @empty
                        <span class="text-sm text-gray-400">{{ __('No answer options yet.') }}</span>
                    @endforelse
                </dd>
            </div>
        </dl>
    </div>
</div>
