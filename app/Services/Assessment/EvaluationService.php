<?php

namespace App\Services\Assessment;

use App\DTOs\Assessment\AssessmentResult;
use App\DTOs\Assessment\AssessmentResultItem;
use App\Models\QuizAttempt;

final class EvaluationService
{
    public function evaluate(QuizAttempt $attempt): AssessmentResult
    {
        $questions = $attempt->quiz->questions()
            ->with('answerOptions')
            ->orderBy('order')
            ->get();
        $answers = $attempt->answers()->with('answerOption')->get()->keyBy('question_id');

        $correct = 0;
        $wrong = 0;
        $unanswered = 0;
        $items = [];

        foreach ($questions as $question) {
            $answer = $answers->get($question->id);
            $correctOption = $question->answerOptions->firstWhere('is_correct', true);

            if ($answer === null) {
                $unanswered++;
            } elseif ($answer->is_correct) {
                $correct++;
            } else {
                $wrong++;
            }

            $items[] = new AssessmentResultItem(
                question: $question->question,
                selectedText: $answer?->answerOption?->option_text,
                isCorrect: (bool) $answer?->is_correct,
                correctText: $correctOption?->option_text,
                points: (int) ($answer?->points ?? 0),
                maxPoints: (int) $question->points,
                answered: $answer !== null,
            );
        }

        return new AssessmentResult(
            correct: $correct,
            wrong: $wrong,
            unanswered: $unanswered,
            total: $questions->count(),
            items: $items,
        );
    }
}
