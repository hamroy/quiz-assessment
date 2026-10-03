<?php

namespace App\Services\Assessment;

use App\Models\QuizAttempt;

final class ScoringService
{
    /**
     * Score a submitted attempt as a 0-100 percentage of obtained points
     * over total possible points.
     */
    public function score(QuizAttempt $attempt): int
    {
        $total = (int) $attempt->quiz->questions()->sum('points');

        if ($total <= 0) {
            return 0;
        }

        $obtained = (int) $attempt->answers()->sum('points');

        return (int) round($obtained / $total * 100);
    }
}
