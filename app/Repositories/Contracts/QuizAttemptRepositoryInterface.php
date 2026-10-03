<?php

namespace App\Repositories\Contracts;

use App\Models\QuizAttempt;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface QuizAttemptRepositoryInterface
{
    public function create(array $data): QuizAttempt;

    public function findById(int $id): QuizAttempt;

    public function update(QuizAttempt $attempt, array $data): QuizAttempt;

    public function listSubmittedForUser(int $userId): LengthAwarePaginator;
}
