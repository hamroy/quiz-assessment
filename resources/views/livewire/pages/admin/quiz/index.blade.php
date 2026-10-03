<?php

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

        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Title') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Status') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Questions') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Created At') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($quizzes as $quiz)
                            <tr>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $quiz->title }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm">
                                    <span class="chip @if ($quiz->status === 'published') bg-green-50 text-green-700 @elseif ($quiz->status === 'archived') bg-red-50 text-red-700 @else bg-yellow-50 text-yellow-700 @endif">
                                        {{ ucfirst($quiz->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $quiz->questions_count }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $quiz->created_at->format('Y-m-d') }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('admin.quizzes.edit', $quiz) }}" wire:navigate class="text-brand-600 hover:text-brand-700">{{ __('Edit') }}</a>
                                        <a href="{{ route('admin.questions.index', $quiz) }}" wire:navigate class="text-sky-600 hover:text-sky-700">{{ __('Questions') }}</a>

                                        @if ($quiz->status === 'published')
                                            <button type="button" wire:click="archive({{ $quiz->id }})" wire:confirm="Archive this quiz?" class="text-yellow-600 hover:text-yellow-700">{{ __('Archive') }}</button>
                                        @else
                                            <button type="button" wire:click="publish({{ $quiz->id }})" class="text-green-600 hover:text-green-700">{{ __('Publish') }}</button>
                                        @endif

                                        <button type="button" wire:click="delete({{ $quiz->id }})" wire:confirm="Delete this quiz?" class="text-red-600 hover:text-red-700">{{ __('Delete') }}</button>
                                    </div>
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
