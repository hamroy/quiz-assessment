<?php

use App\Services\Quiz\QuizService;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.public')] class extends Component
{
    public function with(): array
    {
        return [
            'quizzes' => app(QuizService::class)->listPublished(),
        ];
    }
}; ?>

<div>
    {{-- Hero --}}
    <section class="bg-gradient-to-b from-brand-50 to-gray-50">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 sm:py-16 lg:px-8">
            <div class="max-w-2xl">
                <h1 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                    {{ __('Pahami Diri Anda Lebih Dalam') }}
                </h1>
                <p class="mt-4 text-base text-gray-600 sm:text-lg">
                    {{ __('Ikuti assessment singkat untuk mengenali pola pikir, emosi, dan potensi diri Anda.') }}
                </p>
            </div>
        </div>
    </section>

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-900 sm:text-2xl">{{ __('Assessments') }}</h2>
        </div>

        @if ($quizzes->isEmpty())
            <div class="card p-8 text-center text-gray-500">
                {{ __('No assessments available yet.') }}
            </div>
        @else
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($quizzes as $quiz)
                    <a href="{{ route('quizzes.show', $quiz->slug) }}" wire:navigate class="card group flex flex-col p-6 transition hover:-translate-y-0.5 hover:shadow-md">
                        <div class="flex items-center justify-between">
                            <span class="chip">{{ $quiz->questions_count }} {{ __('Questions') }}</span>
                        </div>

                        <h3 class="mt-4 text-lg font-semibold text-gray-900">{{ $quiz->title }}</h3>

                        @if ($quiz->description)
                            <p class="mt-2 line-clamp-2 flex-1 text-sm text-gray-600">{{ $quiz->description }}</p>
                        @endif

                        <span class="btn-primary mt-5 w-full">
                            {{ __('Start Assessment') }}
                        </span>
                    </a>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $quizzes->links() }}
            </div>
        @endif
    </div>
</div>
