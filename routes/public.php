<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Volt::route('/', 'pages.public.home')
    ->name('home');

Volt::route('quizzes/{slug}', 'pages.public.quiz-detail')
    ->name('quizzes.show');
