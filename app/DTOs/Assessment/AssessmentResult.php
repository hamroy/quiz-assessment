<?php

namespace App\DTOs\Assessment;

final readonly class AssessmentResult
{
    /**
     * @param list<AssessmentResultItem> $items
     */
    public function __construct(
        public int $correct,
        public int $wrong,
        public int $unanswered,
        public int $total,
        public array $items,
    ) {
    }
}
