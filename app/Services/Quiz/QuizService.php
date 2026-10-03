<?php

namespace App\Services\Quiz;

use App\Enums\QuizStatus;
use App\Models\Quiz;
use App\Repositories\Contracts\QuizRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

final class QuizService
{
    public function __construct(
        private readonly QuizRepositoryInterface $repository,
    ) {
    }

    public function create(array $data): Quiz
    {
        return $this->repository->create([
            'title' => $data['title'],
            'slug' => $this->uniqueSlug($data['title']),
            'description' => $data['description'] ?? null,
            'status' => $data['status'] ?? QuizStatus::Draft->value,
        ]);
    }

    public function update(Quiz $quiz, array $data): Quiz
    {
        $payload = [
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'status' => $data['status'] ?? $quiz->status,
        ];

        if ($payload['title'] !== $quiz->title) {
            $payload['slug'] = $this->uniqueSlug($data['title'], $quiz->id);
        }

        return $this->repository->update($quiz, $payload);
    }

    public function delete(Quiz $quiz): void
    {
        if ($this->repository->hasAttempts($quiz)) {
            throw new \DomainException('Quiz with attempts cannot be deleted.');
        }

        $this->repository->delete($quiz);
    }

    public function publish(Quiz $quiz): Quiz
    {
        if (! $this->repository->hasQuestions($quiz)) {
            throw new \DomainException('Quiz must have at least one question to be published.');
        }

        return $this->repository->update($quiz, [
            'status' => QuizStatus::Published->value,
            'published_at' => now(),
        ]);
    }

    public function archive(Quiz $quiz): Quiz
    {
        return $this->repository->update($quiz, [
            'status' => QuizStatus::Archived->value,
        ]);
    }

    public function list(): LengthAwarePaginator
    {
        return $this->repository->list([], ['questions']);
    }

    public function listPublished(): LengthAwarePaginator
    {
        return $this->repository->listPublished([], ['questions']);
    }

    public function findById(int $id): Quiz
    {
        return $this->repository->findById($id);
    }

    public function findPublishedBySlug(string $slug): Quiz
    {
        return $this->repository->findPublishedBySlug($slug);
    }

    public function uniqueSlug(string $title, ?int $exceptId = null): string
    {
        $slug = Str::slug($title);

        if ($slug === '') {
            $slug = 'quiz';
        }

        $base = $slug;
        $suffix = 2;

        while ($this->repository->existsBySlug($slug, $exceptId)) {
            $slug = $base.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }
}
