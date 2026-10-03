<?php

use App\Http\Controllers\Admin\AssessmentResultExportController;
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

        Volt::route('quizzes/{quiz}/questions/{question}/edit', 'pages.admin.question.edit')
            ->name('questions.edit');

        Volt::route('quizzes/{quiz}/questions/{question}/answer-options/create', 'pages.admin.answer-option.create')
            ->name('answer-options.create');

        Volt::route('quizzes/{quiz}/questions/{question}/answer-options/{answerOption}/edit', 'pages.admin.answer-option.edit')
            ->name('answer-options.edit');

        Volt::route('quizzes/{quiz}/questions/{question}/view', 'pages.admin.question.view')
            ->name('questions.view');

        Volt::route('users', 'pages.admin.user.index')
            ->name('users.index');

        Volt::route('users/create', 'pages.admin.user.create')
            ->name('users.create');

        Volt::route('users/{user}/edit', 'pages.admin.user.edit')
            ->name('users.edit');

        Route::get('results/export', AssessmentResultExportController::class)
            ->name('results.export');

        Volt::route('results', 'pages.admin.result.index')
            ->name('results.index');
    });
