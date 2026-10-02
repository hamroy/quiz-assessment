<?php

namespace App\Providers;

use App\Repositories\Contracts\QuizRepositoryInterface;
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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
