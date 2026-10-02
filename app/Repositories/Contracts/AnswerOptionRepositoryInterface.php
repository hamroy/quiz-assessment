<?php

namespace App\Repositories\Contracts;

use App\Models\AnswerOption;
use App\Models\Question;
use Illuminate\Database\Eloquent\Collection;

interface AnswerOptionRepositoryInterface
{
    public function create(array $data): AnswerOption;

    public function maxOrder(Question $question): int;

    public function listForQuestion(Question $question): Collection;

    public function findById(int $id): AnswerOption;

    public function update(AnswerOption $option, array $data): AnswerOption;

    public function delete(AnswerOption $option): void;
}
