<?php

namespace App\Repositories\Contracts;

use App\Models\Quiz;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface QuizRepositoryInterface
{
    public function findById(int $id): Quiz;

    public function findBySlug(string $slug): Quiz;

    public function create(array $data): Quiz;

    public function update(Quiz $quiz, array $data): Quiz;

    public function delete(Quiz $quiz): void;

    public function list(array $with = [], array $withCount = []): LengthAwarePaginator;
}
