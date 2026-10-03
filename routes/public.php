<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware('auth')->group(function () {
    Volt::route('/', 'pages.public.home')
        ->name('home');

    Volt::route('quizzes/{slug}', 'pages.public.quiz-detail')
        ->name('quizzes.show');

    Volt::route('quizzes/{slug}/attempts/{attempt}', 'pages.public.take-quiz')
        ->name('quizzes.take');

    Volt::route('quizzes/{slug}/attempts/{attempt}/result', 'pages.public.result')
        ->name('quizzes.result');
});
