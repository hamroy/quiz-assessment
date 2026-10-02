<?php

namespace App\Repositories;

use App\Models\Question;
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

    public function create(array $data): Question
    {
        return Question::query()->create($data);
    }

    public function maxOrder(Quiz $quiz): int
    {
        return (int) ($quiz->questions()->max('order') ?? 0);
    }
}
