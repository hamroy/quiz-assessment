<?php

namespace App\Services\AnswerOption;

use App\Models\AnswerOption;
use App\Models\Question;
use App\Repositories\Contracts\AnswerOptionRepositoryInterface;

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

    public function create(Question $question, array $data): AnswerOption
    {
        return $this->repository->create([
            'question_id' => $question->id,
            'option_text' => $data['option_text'],
            'is_correct' => $data['is_correct'] ?? false,
            'order' => $data['order'] ?? $this->nextOrder($question),
        ]);
    }
}
