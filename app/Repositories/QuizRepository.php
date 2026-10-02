<?php

namespace App\Repositories;

use App\Models\Quiz;
use App\Repositories\Contracts\QuizRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class QuizRepository implements QuizRepositoryInterface
{
    public function findById(int $id): Quiz
    {
        return Quiz::query()->findOrFail($id);
    }

    public function findBySlug(string $slug): Quiz
    {
        return Quiz::query()->where('slug', $slug)->firstOrFail();
    }

    public function create(array $data): Quiz
    {
        return Quiz::query()->create($data);
    }

    public function update(Quiz $quiz, array $data): Quiz
    {
        $quiz->update($data);

        return $quiz->refresh();
    }

    public function delete(Quiz $quiz): void
    {
        $quiz->delete();
    }

    public function list(array $with = [], array $withCount = []): LengthAwarePaginator
    {
        return Quiz::query()
            ->with($with)
            ->when($withCount, fn ($query) => $query->withCount($withCount))
            ->latest()
            ->paginate(12);
    }
}
