<?php

namespace App\Repositories\Contracts;

use App\Models\AnswerOption;
use App\Models\Question;

interface AnswerOptionRepositoryInterface
{
    public function create(array $data): AnswerOption;

    public function maxOrder(Question $question): int;
}
