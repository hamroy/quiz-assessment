<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::prefix('admin')
    ->middleware(['auth', 'admin'])
    ->name('admin.')
    ->group(function () {
        Volt::route('quizzes', 'pages.admin.quiz.index')
            ->name('quizzes.index');

        Volt::route('quizzes/create', 'pages.admin.quiz.create')
            ->name('quizzes.create');

        Volt::route('quizzes/{quiz}/edit', 'pages.admin.quiz.edit')
            ->name('quizzes.edit');

        Volt::route('quizzes/{quiz}/questions', 'pages.admin.question.index')
            ->name('questions.index');

        Volt::route('quizzes/{quiz}/questions/create', 'pages.admin.question.create')
            ->name('questions.create');
    });
