<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::prefix('admin')
    ->middleware(['auth', 'admin'])
    ->name('admin.')
    ->group(function () {
        Volt::route('quizzes', 'pages.admin.quiz.index')
            ->name('quizzes.index');
    });
