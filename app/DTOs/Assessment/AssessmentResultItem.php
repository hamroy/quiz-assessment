<?php

namespace App\DTOs\Assessment;

final readonly class AssessmentResultItem
{
    public function __construct(
        public string $question,
        public ?string $selectedText,
        public bool $isCorrect,
        public ?string $correctText,
        public int $points,
        public int $maxPoints,
        public bool $answered,
    ) {
    }
}
