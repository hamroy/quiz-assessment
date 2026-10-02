<?php

namespace App\Services\AnswerOption;

use App\Models\AnswerOption;
use App\Models\Question;
use App\Repositories\Contracts\AnswerOptionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

final class AnswerOptionService
{
    public function __construct(
        private readonly AnswerOptionRepositoryInterface $repository,
    ) {
    }

    public function nextOrder(Question $question): int
    {
        return $this->repository->maxOrder($question) + 1;
    }

    public function listForQuestion(Question $question): Collection
    {
        return $this->repository->listForQuestion($question);
    }

    public function create(Question $question, array $data): AnswerOption
    {
        return $this->repository->create([
            'question_id' => $question->id,
            'option_text' => $data['option_text'],
            'is_correct' => $data['is_correct'] ?? false,
            'order' => $data['order'] ?? $this->nextOrder($question),
        ]);
    }

    public function findById(int $id): AnswerOption
    {
        return $this->repository->findById($id);
    }

    public function update(AnswerOption $option, array $data): AnswerOption
    {
        return $this->repository->update($option, [
            'option_text' => $data['option_text'],
            'is_correct' => $data['is_correct'] ?? false,
            'order' => $data['order'],
        ]);
    }

    public function delete(AnswerOption $option): void
    {
        $this->repository->delete($option);
    }
}
