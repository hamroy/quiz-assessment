<?php

namespace App\Repositories;

use App\Enums\QuizAttemptStatus;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Repositories\Contracts\QuizAttemptRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

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

    public function paginateSubmitted(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->submittedQuery($filters)->paginate($perPage);
    }

    public function exportSubmitted(array $filters): Collection
    {
        return $this->submittedQuery($filters)->get();
    }

    public function quizzesWithSubmittedAttempts(): Collection
    {
        return Quiz::query()
            ->whereExists(
                QuizAttempt::query()
                    ->selectRaw('1')
                    ->whereColumn('quiz_attempts.quiz_id', 'quizzes.id')
                    ->where('quiz_attempts.status', QuizAttemptStatus::Submitted->value)
            )
            ->orderBy('title')
            ->get();
    }

    /**
     * @param  array{quiz_id?:int|null,search?:string|null,from?:string|null,to?:string|null}  $filters
     */
    private function submittedQuery(array $filters): Builder
    {
        $query = QuizAttempt::query()
            ->where('status', QuizAttemptStatus::Submitted->value)
            ->whereNotNull('user_id')
            ->with(['quiz', 'user']);

        if (! empty($filters['quiz_id'])) {
            $query->where('quiz_id', $filters['quiz_id']);
        }

        if (! empty($filters['search'])) {
            $term = '%'.$filters['search'].'%';

            $query->whereHas('user', fn (Builder $user) => $user
                ->where('users.name', 'like', $term)
                ->orWhere('users.email', 'like', $term));
        }

        if (! empty($filters['from'])) {
            $query->whereDate('submitted_at', '>=', $filters['from']);
        }

        if (! empty($filters['to'])) {
            $query->whereDate('submitted_at', '<=', $filters['to']);
        }

        return $query->latest('submitted_at');
    }
}
