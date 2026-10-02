<?php

use App\Services\Quiz\QuizService;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    public ?int $deletingId = null;

    public string $deleteError = '';

    /**
     * Provide the paginated quiz list to the view.
     *
     * Business logic and data access live in QuizService/QuizRepository;
     * this component only orchestrates presentation.
     */
    public function with(): array
    {
        return [
            'quizzes' => app(QuizService::class)->list(),
        ];
    }

    public function confirmDelete(int $quizId): void
    {
        $this->deletingId = $quizId;
        $this->deleteError = '';
    }

    public function cancelDelete(): void
    {
        $this->deletingId = null;
        $this->deleteError = '';
    }

    public function delete(QuizService $service): void
    {
        if ($this->deletingId === null) {
            return;
        }

        try {
            $service->delete($service->findById($this->deletingId));
            $this->deletingId = null;
            $this->deleteError = '';
        } catch (\DomainException $e) {
            $this->deleteError = $e->getMessage();
        }
    }
}; ?>

<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="font-semibold text-xl">Quizzes</h2>
                        <a href="{{ route('admin.quizzes.create') }}" wire:navigate
                            class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            {{ __('Create Quiz') }}
                        </a>
                    </div>

                    @if ($quizzes->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400 py-8 text-center">
                            No quizzes yet.
                        </p>
                    @else
                        @if ($deleteError)
                            <p class="mb-4 text-sm text-red-600 dark:text-red-400">{{ $deleteError }}</p>
                        @endif
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Title</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Questions</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Created At</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($quizzes as $quiz)
                                        <tr>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $quiz->title }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    @if ($quiz->status === 'published') bg-green-500 text-white
                                                    @elseif ($quiz->status === 'archived') bg-red-500 text-white
                                                    @else bg-yellow-400 text-yellow-900
                                                    @endif">
                                                    {{ ucfirst($quiz->status) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $quiz->questions_count }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $quiz->created_at->format('Y-m-d') }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('admin.quizzes.edit', $quiz) }}" wire:navigate class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">Edit</a>

                                                @if ($deletingId === $quiz->id)
                                                    <span class="ms-3 text-red-500">Delete this quiz?</span>
                                                    <button wire:click="delete" class="ms-2 text-red-600 hover:text-red-900 font-semibold">Yes</button>
                                                    <button wire:click="cancelDelete" class="ms-2 text-gray-500 hover:text-gray-700">No</button>
                                                @else
                                                    <button wire:click="confirmDelete({{ $quiz->id }})" class="ms-3 text-red-600 hover:text-red-900">Delete</button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $quizzes->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
