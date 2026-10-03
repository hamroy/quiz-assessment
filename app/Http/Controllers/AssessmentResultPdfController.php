<?php

namespace App\Http\Controllers;

use App\Enums\QuizAttemptStatus;
use App\Models\QuizAttempt;
use App\Services\Assessment\EvaluationService;
use App\Services\Quiz\QuizService;
use Barryvdh\DomPDF\Facade\Pdf;

class AssessmentResultPdfController extends Controller
{
    public function __invoke(string $slug, QuizAttempt $attempt, QuizService $quizService, EvaluationService $evaluation)
    {
        $quiz = $quizService->findPublishedBySlug($slug);

        if ($attempt->quiz_id !== $quiz->id
            || $attempt->user_id !== auth()->id()
            || $attempt->status !== QuizAttemptStatus::Submitted->value) {
            abort(404);
        }

        $attempt->load('quiz');

        $pdf = Pdf::loadView('pdf.assessment-result', [
            'attempt' => $attempt,
            'result' => $evaluation->evaluate($attempt),
        ]);

        return $pdf->download('assessment-result-'.$attempt->id.'.pdf');
    }
}
