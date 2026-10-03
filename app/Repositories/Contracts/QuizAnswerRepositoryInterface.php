<?php

namespace App\Repositories\Contracts;

use App\Models\AnswerOption;
use App\Models\Question;
use App\Models\QuizAnswer;
use App\Models\QuizAttempt;
use Illuminate\Database\Eloquent\Collection;

interface QuizAnswerRepositoryInterface
{
    public function upsert(QuizAttempt $attempt, Question $question, AnswerOption $option, bool $isCorrect, int $points): QuizAnswer;

    public function listForAttempt(QuizAttempt $attempt): Collection;
}
