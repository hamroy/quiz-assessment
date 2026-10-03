<?php

namespace App\Services\Assessment;

use App\Enums\QuizAttemptStatus;
use App\Enums\QuizStatus;
use App\Models\AnswerOption;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\QuizAttempt;
use App\Models\User;
use App\Repositories\Contracts\QuizAnswerRepositoryInterface;
use App\Repositories\Contracts\QuizAttemptRepositoryInterface;
use App\Repositories\Contracts\QuizRepositoryInterface;
use Illuminate\Support\Facades\DB;

final class QuizAttemptService
{
    public function __construct(
        private readonly QuizAttemptRepositoryInterface $attempts,
        private readonly QuizAnswerRepositoryInterface $answers,
        private readonly QuizRepositoryInterface $quizzes,
        private readonly ScoringService $scoring,
    ) {
    }

    public function start(Quiz $quiz, ?User $user = null): QuizAttempt
    {
        if ($quiz->status !== QuizStatus::Published->value) {
            throw new \DomainException('Only published quizzes can be started.');
        }

        if (! $this->quizzes->hasQuestions($quiz)) {
            throw new \DomainException('Quiz has no questions.');
        }

        return $this->attempts->create([
            'quiz_id' => $quiz->id,
            'user_id' => $user?->id,
            'started_at' => now(),
            'status' => QuizAttemptStatus::InProgress->value,
        ]);
    }

    public function findById(int $id): QuizAttempt
    {
        return $this->attempts->findById($id);
    }

    public function listSubmittedForUser(int $userId): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->attempts->listSubmittedForUser($userId);
    }

    public function submit(QuizAttempt $attempt): QuizAttempt
    {
        if ($attempt->status !== QuizAttemptStatus::InProgress->value) {
            throw new \DomainException('Attempt is already submitted.');
        }

        return DB::transaction(function () use ($attempt) {
            $score = $this->scoring->score($attempt);

            return $this->attempts->update($attempt, [
                'status' => QuizAttemptStatus::Submitted->value,
                'submitted_at' => now(),
                'score' => $score,
            ]);
        });
    }

    public function answer(QuizAttempt $attempt, Question $question, AnswerOption $option): QuizAnswer
    {
        if ($attempt->status !== QuizAttemptStatus::InProgress->value) {
            throw new \DomainException('Attempt is already submitted.');
        }

        if ($attempt->quiz_id !== $question->quiz_id) {
            throw new \DomainException('Question does not belong to this attempt.');
        }

        if ($option->question_id !== $question->id) {
            throw new \DomainException('Answer option does not belong to this question.');
        }

        $isCorrect = (bool) $option->is_correct;
        $points = $isCorrect ? (int) $question->points : 0;

        return $this->answers->upsert($attempt, $question, $option, $isCorrect, $points);
    }
}
