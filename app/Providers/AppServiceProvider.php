<?php

namespace App\Providers;

use App\Repositories\Contracts\AnswerOptionRepositoryInterface;
use App\Repositories\Contracts\QuestionRepositoryInterface;
use App\Repositories\Contracts\QuizRepositoryInterface;
use App\Repositories\AnswerOptionRepository;
use App\Repositories\QuestionRepository;
use App\Repositories\QuizRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            QuizRepositoryInterface::class,
            QuizRepository::class,
        );

        $this->app->bind(
            QuestionRepositoryInterface::class,
            QuestionRepository::class,
        );

        $this->app->bind(
            AnswerOptionRepositoryInterface::class,
            AnswerOptionRepository::class,
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
