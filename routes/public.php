<?php

use Livewire\Volt\Volt;

Volt::route('/', 'pages.public.home')
    ->name('home');

Volt::route('quizzes/{slug}', 'pages.public.quiz-detail')
    ->name('quizzes.show');

Volt::route('quizzes/{slug}/attempts/{attempt}', 'pages.public.take-quiz')
    ->name('quizzes.take');
