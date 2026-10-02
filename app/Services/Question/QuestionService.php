<?php

namespace App\Services\Question;

use App\Models\Question;
use App\Models\Quiz;
use App\Repositories\Contracts\QuestionRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class QuestionService
{
    public function __construct(
        private readonly QuestionRepositoryInterface $repository,
    ) {
    }

    public function listForQuiz(Quiz $quiz): LengthAwarePaginator
    {
        return $this->repository->listForQuiz($quiz);
    }

    public function nextOrder(Quiz $quiz): int
    {
        return $this->repository->maxOrder($quiz) + 1;
    }

    public function create(Quiz $quiz, array $data): Question
    {
        return $this->repository->create([
            'quiz_id' => $quiz->id,
            'question' => $data['question'],
            'type' => $data['type'],
            'points' => $data['points'],
            'order' => $data['order'] ?? $this->nextOrder($quiz),
        ]);
    }

    public function update(Question $question, array $data): Question
    {
        return $this->repository->update($question, [
            'question' => $data['question'],
            'type' => $data['type'],
            'points' => $data['points'],
            'order' => $data['order'],
        ]);
    }

    public function delete(Question $question): void
    {
        $this->repository->delete($question);
    }

    public function findById(int $id): Question
    {
        return $this->repository->findById($id);
    }
}
