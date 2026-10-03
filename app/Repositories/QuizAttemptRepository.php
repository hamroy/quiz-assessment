<?php

namespace App\Repositories;

use App\Models\QuizAttempt;
use App\Repositories\Contracts\QuizAttemptRepositoryInterface;

final class QuizAttemptRepository implements QuizAttemptRepositoryInterface
{
    public function create(array $data): QuizAttempt
    {
        return QuizAttempt::query()->create($data);
    }

    public function findById(int $id): QuizAttempt
    {
        return QuizAttempt::query()->findOrFail($id);
    }

    public function update(QuizAttempt $attempt, array $data): QuizAttempt
    {
        $attempt->update($data);

        return $attempt->refresh();
    }
}
