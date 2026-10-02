<?php

namespace App\Services\Quiz;

use App\Models\Quiz;
use App\Repositories\Contracts\QuizRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class QuizService
{
    public function __construct(
        private readonly QuizRepositoryInterface $repository,
    ) {
    }

    public function create(array $data): Quiz
    {
        return $this->repository->create($data);
    }

    public function update(Quiz $quiz, array $data): Quiz
    {
        return $this->repository->update($quiz, $data);
    }

    public function delete(Quiz $quiz): void
    {
        $this->repository->delete($quiz);
    }

    public function list(): LengthAwarePaginator
    {
        return $this->repository->list([], ['questions']);
    }

    public function findById(int $id): Quiz
    {
        return $this->repository->findById($id);
    }
}
