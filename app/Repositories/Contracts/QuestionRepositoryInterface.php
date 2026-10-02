<?php

namespace App\Repositories\Contracts;

use App\Models\Quiz;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface QuestionRepositoryInterface
{
    public function listForQuiz(Quiz $quiz): LengthAwarePaginator;
}
