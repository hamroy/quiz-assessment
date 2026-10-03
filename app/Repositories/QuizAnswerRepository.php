<?php

namespace App\Repositories;

use App\Models\AnswerOption;
use App\Models\Question;
use App\Models\QuizAnswer;
use App\Models\QuizAttempt;
use App\Repositories\Contracts\QuizAnswerRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

final class QuizAnswerRepository implements QuizAnswerRepositoryInterface
{
    public function upsert(QuizAttempt $attempt, Question $question, AnswerOption $option, bool $isCorrect, int $points): QuizAnswer
    {
        return QuizAnswer::query()->updateOrCreate(
            [
                'quiz_attempt_id' => $attempt->id,
                'question_id' => $question->id,
            ],
            [
                'answer_option_id' => $option->id,
                'is_correct' => $isCorrect,
                'points' => $points,
            ],
        );
    }

    public function listForAttempt(QuizAttempt $attempt): Collection
    {
        return $attempt->answers()->get();
    }
}
