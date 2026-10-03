<?php

use App\Http\Controllers\AssessmentResultPdfController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

// Guest-accessible pages (admins still blocked).
Route::middleware('non-admin')->group(function () {
    Volt::route('/', 'pages.public.home')
        ->name('home');

    Volt::route('quizzes/{slug}', 'pages.public.quiz-detail')
        ->name('quizzes.show');
});

// Pages that require an authenticated (non-admin) user.
Route::middleware(['auth', 'non-admin'])->group(function () {
    Volt::route('results', 'pages.public.results')
        ->name('results.index');

    Volt::route('quizzes/{slug}/attempts/{attempt}', 'pages.public.take-quiz')
        ->name('quizzes.take');

    Volt::route('quizzes/{slug}/attempts/{attempt}/result', 'pages.public.result')
        ->name('quizzes.result');

    Route::get('quizzes/{slug}/attempts/{attempt}/result/pdf', AssessmentResultPdfController::class)
        ->name('quizzes.result.pdf');
});
