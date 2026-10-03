<?php

namespace Tests\Feature\Public;

use App\Models\Quiz;
use App\Models\User;
use App\Services\Assessment\QuizAttemptService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResultPdfTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
    }

    public function test_pdf_downloads_for_own_submitted_attempt(): void
    {
        $quiz = $this->publishedQuiz();
        $attempt = app(QuizAttemptService::class)->start($quiz, auth()->user());
        $attempt->update(['status' => 'submitted', 'score' => 75, 'submitted_at' => now()]);

        $this->get(route('quizzes.result.pdf', ['slug' => $quiz->slug, 'attempt' => $attempt->id]))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }

    public function test_pdf_returns_404_for_another_users_attempt(): void
    {
        $quiz = $this->publishedQuiz();
        $attempt = app(QuizAttemptService::class)->start($quiz, auth()->user());
        $attempt->update(['status' => 'submitted', 'score' => 75, 'submitted_at' => now()]);

        $this->actingAs(User::factory()->create());

        $this->get(route('quizzes.result.pdf', ['slug' => $quiz->slug, 'attempt' => $attempt->id]))
            ->assertNotFound();
    }

    public function test_pdf_returns_404_for_in_progress_attempt(): void
    {
        $quiz = $this->publishedQuiz();
        $attempt = app(QuizAttemptService::class)->start($quiz, auth()->user());

        $this->get(route('quizzes.result.pdf', ['slug' => $quiz->slug, 'attempt' => $attempt->id]))
            ->assertNotFound();
    }

    private function publishedQuiz(): Quiz
    {
        $quiz = Quiz::factory()->create(['status' => 'published']);

        $question = $quiz->questions()->create([
            'question' => 'First question?',
            'type' => 'single_choice',
            'points' => 5,
            'order' => 1,
        ]);
        $question->answerOptions()->createMany([
            ['option_text' => 'Correct A', 'is_correct' => true, 'order' => 1],
            ['option_text' => 'Wrong A', 'is_correct' => false, 'order' => 2],
        ]);

        return $quiz;
    }
}
