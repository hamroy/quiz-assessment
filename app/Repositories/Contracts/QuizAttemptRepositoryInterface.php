<?php

namespace App\Repositories\Contracts;

use App\Models\QuizAttempt;

interface QuizAttemptRepositoryInterface
{
    public function create(array $data): QuizAttempt;

    public function findById(int $id): QuizAttempt;

    public function update(QuizAttempt $attempt, array $data): QuizAttempt;
}
