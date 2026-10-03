<?php

use App\Services\Assessment\AssessmentResultReportService;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new #[Layout('layouts.app')] class extends Component
{
    use WithPagination;

    public string $search = '';

    public string $quizId = '';

    public string $dateFrom = '';

    public string $dateTo = '';

    public function updated(string $property): void
    {
        if ($property !== 'page') {
            $this->resetPage();
        }
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'quizId', 'dateFrom', 'dateTo']);
        $this->resetPage();
    }

    public function with(AssessmentResultReportService $report): array
    {
        $filters = $this->filters();

        return [
            'filters' => $filters,
            'attempts' => $report->paginate($filters),
            'quizzes' => $report->filterableQuizzes(),
        ];
    }

    /**
     * @return array{quiz_id:int|null,search:string|null,from:string|null,to:string|null}
     */
    private function filters(): array
    {
        $search = trim($this->search);

        return [
            'quiz_id' => $this->quizId === '' ? null : (int) $this->quizId,
            'search' => $search === '' ? null : $search,
            'from' => $this->dateFrom === '' ? null : $this->dateFrom,
            'to' => $this->dateTo === '' ? null : $this->dateTo,
        ];
    }
}; ?>

<div class="p-4 sm:p-6 lg:p-8">
    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <h2 class="text-lg font-semibold text-gray-900">{{ __('Assessment Results') }}</h2>

        <a href="{{ route('admin.results.export', array_filter([
            'quiz_id' => $filters['quiz_id'],
            'search' => $filters['search'],
            'from' => $filters['from'],
            'to' => $filters['to'],
        ])) }}" class="btn-secondary">
            {{ __('Export Excel') }}
        </a>
    </div>

    <div class="card mb-4 p-4">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div>
                <x-input-label for="search" :value="__('Search name or email')" />
                <input wire:model.live.debounce.300ms="search" id="search" type="search"
                       class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500" />
            </div>
            <div>
                <x-input-label for="quizId" :value="__('Quiz')" />
                <select wire:model.live="quizId" id="quizId"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
                    <option value="">{{ __('All quizzes') }}</option>
                    @foreach ($quizzes as $quiz)
                        <option value="{{ $quiz->id }}">{{ $quiz->title }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <x-input-label for="dateFrom" :value="__('From')" />
                <input wire:model.live="dateFrom" id="dateFrom" type="date"
                       class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500" />
            </div>
            <div>
                <x-input-label for="dateTo" :value="__('To')" />
                <input wire:model.live="dateTo" id="dateTo" type="date"
                       class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500" />
            </div>
        </div>

        <div class="mt-4 flex justify-end">
            <button type="button" wire:click="resetFilters" class="btn-secondary">{{ __('Reset Filters') }}</button>
        </div>
    </div>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Name') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Email') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Quiz') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Type') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Score') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Submitted At') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200" wire:loading.class="opacity-50">
                    @forelse ($attempts as $attempt)
                        <tr wire:key="attempt-{{ $attempt->id }}">
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $attempt->user->name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $attempt->user->email }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $attempt->quiz->title }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $attempt->quiz->type_label }}</td>
                            <td class="px-4 py-3 text-sm font-semibold text-gray-900">{{ $attempt->score }}%</td>
                            <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-500">{{ $attempt->submitted_at?->format('Y-m-d H:i') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">{{ __('No results found.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-gray-100 px-4 py-3">{{ $attempts->links() }}</div>
    </div>
</div>
