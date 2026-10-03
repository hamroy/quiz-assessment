<?php

use App\Enums\QuizType;
use App\Services\Quiz\QuizService;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    public string $deleteError = '';

    public string $publishError = '';

    public function with(): array
    {
        return [
            'quizzes' => app(QuizService::class)->list(),
        ];
    }

    public function delete(int $quizId, QuizService $service): void
    {
        try {
            $service->delete($service->findById($quizId));
            $this->deleteError = '';
        } catch (\DomainException $e) {
            $this->deleteError = $e->getMessage();
        }
    }

    public function publish(int $quizId, QuizService $service): void
    {
        try {
            $service->publish($service->findById($quizId));
            $this->publishError = '';
        } catch (\DomainException $e) {
            $this->publishError = $e->getMessage();
        }
    }

    public function archive(int $quizId, QuizService $service): void
    {
        $service->archive($service->findById($quizId));
        $this->publishError = '';
    }
}; ?>

<div class="p-4 sm:p-6 lg:p-8">
    <div class="mb-4 flex items-center justify-between">
        <h2 class="text-lg font-semibold text-gray-900">{{ __('Quizzes') }}</h2>
        <a href="{{ route('admin.quizzes.create') }}" wire:navigate class="btn-primary">
            {{ __('Create Quiz') }}
        </a>
    </div>

    @if ($quizzes->isEmpty())
        <div class="card p-8 text-center text-gray-500">
            {{ __('No quizzes yet.') }}
        </div>
    @else
        @if ($deleteError)
            <p class="mb-4 text-sm text-red-600">{{ $deleteError }}</p>
        @endif
        @if ($publishError)
            <p class="mb-4 text-sm text-red-600">{{ $publishError }}</p>
        @endif

        <div class="card">
            <div>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="rounded-tl-2xl px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Title') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Type') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Status') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Questions') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Created At') }}</th>
                            <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($quizzes as $quiz)
                            <tr>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $quiz->title }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                    {{ QuizType::tryFrom($quiz->type)?->label() ?? ucfirst($quiz->type) }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm">
                                    <span class="chip @if ($quiz->status === 'published') bg-green-50 text-green-700 @elseif ($quiz->status === 'archived') bg-red-50 text-red-700 @else bg-yellow-50 text-yellow-700 @endif">
                                        {{ ucfirst($quiz->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $quiz->questions_count }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $quiz->created_at->format('Y-m-d') }}</td>
                                <td class="relative px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                    <x-dropdown align="right" width="48">
                                        <x-slot name="trigger">
                                            <button type="button" class="inline-flex items-center justify-center rounded-lg p-1.5 text-gray-500 hover:bg-gray-100 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2">
                                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                                </svg>
                                            </button>
                                        </x-slot>
                                        <x-slot name="content">
                                            <a href="{{ route('admin.quizzes.edit', $quiz) }}" wire:navigate class="block w-full px-4 py-2 text-center text-sm text-gray-700 hover:bg-gray-100">{{ __('Edit') }}</a>
                                            <a href="{{ route('admin.questions.index', $quiz) }}" wire:navigate class="block w-full px-4 py-2 text-center text-sm text-gray-700 hover:bg-gray-100">{{ __('Questions') }}</a>

                                            @if ($quiz->status === 'published')
                                                <button type="button" wire:click="archive({{ $quiz->id }})" wire:confirm="Archive this quiz?" class="block w-full px-4 py-2 text-center text-sm text-yellow-700 hover:bg-gray-100">{{ __('Archive') }}</button>
                                            @else
                                                <button type="button" wire:click="publish({{ $quiz->id }})" class="block w-full px-4 py-2 text-center text-sm text-green-700 hover:bg-gray-100">{{ __('Publish') }}</button>
                                            @endif

                                            <button type="button" wire:click="delete({{ $quiz->id }})" wire:confirm="Delete this quiz?" class="block w-full px-4 py-2 text-center text-sm text-red-600 hover:bg-gray-100">{{ __('Delete') }}</button>
                                        </x-slot>
                                    </x-dropdown>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="border-t border-gray-100 px-4 py-3">
                {{ $quizzes->links() }}
            </div>
        </div>
    @endif
</div>
