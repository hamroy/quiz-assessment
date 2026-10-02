<?php

namespace App\Providers;

use App\Repositories\Contracts\QuestionRepositoryInterface;
use App\Repositories\Contracts\QuizRepositoryInterface;
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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
