<?php

namespace App\Providers;

use App\Models\User;
use App\Repositories\AnswerOptionRepository;
use App\Repositories\Contracts\AnswerOptionRepositoryInterface;
use App\Repositories\Contracts\QuestionRepositoryInterface;
use App\Repositories\Contracts\QuizAnswerRepositoryInterface;
use App\Repositories\Contracts\QuizAttemptRepositoryInterface;
use App\Repositories\Contracts\QuizRepositoryInterface;
use App\Repositories\QuestionRepository;
use App\Repositories\QuizAnswerRepository;
use App\Repositories\QuizAttemptRepository;
use App\Repositories\QuizRepository;
use Illuminate\Support\Facades\Gate;
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

        $this->app->bind(
            QuizAttemptRepositoryInterface::class,
            QuizAttemptRepository::class,
        );

        $this->app->bind(
            QuizAnswerRepositoryInterface::class,
            QuizAnswerRepository::class,
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Scramble's RestrictedDocsAccess only allows /docs/api in `local`.
        // Admins may view it anywhere; everyone else gets a 403 outside local.
        Gate::define('viewApiDocs', fn (User $user) => $user->isAdmin());
    }
}
