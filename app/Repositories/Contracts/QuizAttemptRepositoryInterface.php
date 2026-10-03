<?php

namespace App\Repositories\Contracts;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface QuizAttemptRepositoryInterface
{
    public function create(array $data): QuizAttempt;

    public function findById(int $id): QuizAttempt;

    public function update(QuizAttempt $attempt, array $data): QuizAttempt;

    public function listSubmittedForUser(int $userId): LengthAwarePaginator;

    /**
     * @param  array{quiz_id?:int|null,search?:string|null,from?:string|null,to?:string|null}  $filters
     */
    public function paginateSubmitted(array $filters, int $perPage = 15): LengthAwarePaginator;

    /**
     * @param  array{quiz_id?:int|null,search?:string|null,from?:string|null,to?:string|null}  $filters
     */
    public function exportSubmitted(array $filters): Collection;

    /**
     * Quizzes that have at least one submitted attempt, for filter dropdowns.
     *
     * @return Collection<int, Quiz>
     */
    public function quizzesWithSubmittedAttempts(): Collection;
}
