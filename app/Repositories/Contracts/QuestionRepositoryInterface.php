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

    public function update(Question $question, array $data): Question;

    public function delete(Question $question): void;

    public function findById(int $id): Question;
}
