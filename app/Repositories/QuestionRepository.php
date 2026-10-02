<?php

namespace App\Repositories;

use App\Models\Quiz;
use App\Repositories\Contracts\QuestionRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class QuestionRepository implements QuestionRepositoryInterface
{
    public function listForQuiz(Quiz $quiz): LengthAwarePaginator
    {
        return $quiz->questions()
            ->withCount('answerOptions')
            ->orderBy('order')
            ->paginate(12);
    }
}
