<x-app-layout>
    <x-slot name="header">{{ __('Dashboard') }}</x-slot>

    <div class="p-4 sm:p-6 lg:p-8">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            {{-- Total Quizzes --}}
            <div class="card p-5">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-brand-50 text-brand-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Quiz::count() }}</p>
                        <p class="text-sm text-gray-500">{{ __('Total Quizzes') }}</p>
                    </div>
                </div>
            </div>

            {{-- Total Users --}}
            <div class="card p-5">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-brand-50 text-brand-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ \App\Models\User::count() }}</p>
                        <p class="text-sm text-gray-500">{{ __('Total Users') }}</p>
                    </div>
                </div>
            </div>

            {{-- Total Questions --}}
            <div class="card p-5">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-brand-50 text-brand-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Question::count() }}</p>
                        <p class="text-sm text-gray-500">{{ __('Total Questions') }}</p>
                    </div>
                </div>
            </div>

            {{-- Total Attempts --}}
            <div class="card p-5">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-brand-50 text-brand-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ \App\Models\QuizAttempt::count() }}</p>
                        <p class="text-sm text-gray-500">{{ __('Total Attempts') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Quizzes --}}
        <div class="mt-8 card overflow-hidden">
            <div class="border-b border-gray-100 px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-900">{{ __('Recent Quizzes') }}</h2>
            </div>
            <div class="p-6">
                @php
                    $recentQuizzes = \App\Models\Quiz::withCount('questions')->orderByDesc('created_at')->limit(5)->get();
                @endphp
                @if ($recentQuizzes->isEmpty())
                    <p class="py-8 text-center text-sm text-gray-500">
                        {{ __('No quizzes yet.') }}
                        <a href="{{ route('admin.quizzes.create') }}" wire:navigate class="text-brand-600 hover:text-brand-700">{{ __('Create one') }}</a>.
                    </p>
                @else
                    <div class="space-y-3">
                        @foreach ($recentQuizzes as $quiz)
                            <a href="{{ route('admin.quizzes.edit', $quiz) }}" wire:navigate
                               class="flex items-center justify-between rounded-xl border border-gray-100 bg-gray-50 px-4 py-3 transition hover:bg-gray-100">
                                <div class="flex items-center gap-3">
                                    <span class="chip">{{ $quiz->questions_count }} {{ __('Questions') }}</span>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $quiz->title }}</p>
                                        <p class="text-xs text-gray-500">{{ $quiz->created_at->format('Y-m-d') }}</p>
                                    </div>
                                </div>
                                <span class="chip
                                    @if ($quiz->status === 'published') bg-green-50 text-green-700
                                    @elseif ($quiz->status === 'archived') bg-red-50 text-red-700
                                    @else bg-yellow-50 text-yellow-700 @endif">
                                    {{ ucfirst($quiz->status) }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="mt-8 card p-6">
            <h2 class="mb-4 text-lg font-semibold text-gray-900">{{ __('Quick Actions') }}</h2>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.quizzes.create') }}" wire:navigate class="btn-primary">
                    {{ __('Create Quiz') }}
                </a>
                <a href="{{ route('admin.quizzes.index') }}" wire:navigate class="btn-secondary">
                    {{ __('Manage Quizzes') }}
                </a>
                <a href="{{ route('admin.users.index') }}" wire:navigate class="btn-secondary">
                    {{ __('Manage Users') }}
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
