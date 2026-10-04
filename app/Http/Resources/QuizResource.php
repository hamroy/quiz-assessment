<?php

namespace App\Http\Resources;

use App\Enums\QuizType;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Public, read-only representation of a quiz.
 *
 * Never exposes the `status` column or anything marked `is_correct`.
 *
 * @property-read Quiz $resource
 */
class QuizResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'slug' => $this->resource->slug,
            'description' => $this->resource->description,
            'type' => (string) $this->resource->type,
            'type_label' => $this->typeLabel(),
            'published_at' => $this->resource->published_at?->toIso8601String(),
            'questions_count' => (int) ($this->resource->questions_count ?? $this->resource->questions()->count()),
        ];
    }

    private function typeLabel(): string
    {
        return QuizType::tryFrom((string) $this->resource->type)?->label()
            ?? ucfirst(str_replace('_', ' ', (string) $this->resource->type));
    }
}
