<?php

namespace App\Services\Assessment;

use App\Models\Quiz;
use App\Repositories\Contracts\QuizAttemptRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

final class AssessmentResultReportService
{
    public function __construct(
        private readonly QuizAttemptRepositoryInterface $attempts,
    ) {}

    /**
     * @param  array{quiz_id?:int|null,search?:string|null,from?:string|null,to?:string|null}  $filters
     */
    public function paginate(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->attempts->paginateSubmitted($filters, $perPage);
    }

    /**
     * @param  array{quiz_id?:int|null,search?:string|null,from?:string|null,to?:string|null}  $filters
     */
    public function all(array $filters): Collection
    {
        return $this->attempts->exportSubmitted($filters);
    }

    /**
     * @return Collection<int, Quiz>
     */
    public function filterableQuizzes(): Collection
    {
        return $this->attempts->quizzesWithSubmittedAttempts();
    }
}
