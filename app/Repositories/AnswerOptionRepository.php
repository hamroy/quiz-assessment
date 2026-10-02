<?php

namespace App\Repositories;

use App\Models\AnswerOption;
use App\Models\Question;
use App\Repositories\Contracts\AnswerOptionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

final class AnswerOptionRepository implements AnswerOptionRepositoryInterface
{
    public function create(array $data): AnswerOption
    {
        return AnswerOption::query()->create($data);
    }

    public function maxOrder(Question $question): int
    {
        return (int) ($question->answerOptions()->max('order') ?? 0);
    }

    public function listForQuestion(Question $question): Collection
    {
        return $question->answerOptions()->orderBy('order')->get();
    }
}
