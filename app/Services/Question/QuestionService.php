<?php

namespace App\Services\Question;

use App\Models\Quiz;
use App\Repositories\Contracts\QuestionRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class QuestionService
{
    public function __construct(
        private readonly QuestionRepositoryInterface $repository,
    ) {
    }

    public function listForQuiz(Quiz $quiz): LengthAwarePaginator
    {
        return $this->repository->listForQuiz($quiz);
    }
}
