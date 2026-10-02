<?php

namespace App\Repositories\Contracts;

use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface QuestionRepositoryInterface
{
    public function listForQuiz(Quiz $quiz): LengthAwarePaginator;

    public function create(array $data): Question;

    public function maxOrder(Quiz $quiz): int;
}
