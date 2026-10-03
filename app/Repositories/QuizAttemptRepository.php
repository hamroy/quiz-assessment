<?php

namespace App\Repositories;

use App\Enums\QuizAttemptStatus;
use App\Models\QuizAttempt;
use App\Repositories\Contracts\QuizAttemptRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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

    public function listSubmittedForUser(int $userId): LengthAwarePaginator
    {
        return QuizAttempt::query()
            ->where('user_id', $userId)
            ->where('status', QuizAttemptStatus::Submitted->value)
            ->with('quiz')
            ->latest('submitted_at')
            ->paginate(10);
    }
}
