<?php

namespace Tests\Feature\Public;

use App\Enums\QuestionType;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Services\Assessment\QuizAttemptService;
use App\Services\Assessment\ScoringService;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuizSubmitTest extends TestCase
{
    use RefreshDatabase;

    public function test_score_is_percentage_of_obtained_points(): void
    {
        $quiz = $this->publishedQuiz();
        $attempt = app(QuizAttemptService::class)->start($quiz);

        // Answer Q1 correctly (5 pts), Q2 wrong (0 pts) -> 5 / 8 = 62.5 -> 63
        $q1 = $quiz->questions()->where('order', 1)->first();
        $q2 = $quiz->questions()->where('order', 2)->first();

        app(QuizAttemptService::class)->answer($attempt, $q1, $q1->answerOptions()->where('is_correct', true)->first());
        app(QuizAttemptService::class)->answer($attempt, $q2, $q2->answerOptions()->where('is_correct', false)->first());

        $score = app(ScoringService::class)->score($attempt);

        $this->assertSame(63, $score);
    }

    public function test_score_is_zero_when_no_questions(): void
    {
        $quiz = Quiz::factory()->create(['status' => 'published']);
        $attempt = new QuizAttempt(['quiz_id' => $quiz->id, 'status' => 'in_progress', 'score' => 0]);
        $attempt->save();

        $this->assertSame(0, app(ScoringService::class)->score($attempt));
    }

    public function test_submit_marks_attempt_submitted_and_sets_score(): void
    {
        $quiz = $this->publishedQuiz();
        $attempt = app(QuizAttemptService::class)->start($quiz);

        $q1 = $quiz->questions()->where('order', 1)->first();
        app(QuizAttemptService::class)->answer($attempt, $q1, $q1->answerOptions()->where('is_correct', true)->first());

        $submitted = app(QuizAttemptService::class)->submit($attempt);

        $this->assertSame('submitted', $submitted->status);
        $this->assertNotNull($submitted->submitted_at);
        $this->assertGreaterThan(0, $submitted->score);
    }

    public function test_submitted_attempt_cannot_be_resubmitted(): void
    {
        $quiz = $this->publishedQuiz();
        $attempt = app(QuizAttemptService::class)->start($quiz);
        $attempt->update(['status' => 'submitted']);

        $this->expectException(\DomainException::class);

        app(QuizAttemptService::class)->submit($attempt);
    }

    public function test_result_page_renders_for_submitted_attempt(): void
    {
        $quiz = $this->publishedQuiz();
        $attempt = app(QuizAttemptService::class)->start($quiz);
        $attempt->update(['status' => 'submitted', 'score' => 75, 'submitted_at' => now()]);

        $this->get(route('quizzes.result', ['slug' => $quiz->slug, 'attempt' => $attempt->id]))
            ->assertOk()
            ->assertSee('75');
    }

    public function test_result_page_returns_404_for_in_progress_attempt(): void
    {
        $quiz = $this->publishedQuiz();
        $attempt = app(QuizAttemptService::class)->start($quiz);

        $this->get(route('quizzes.result', ['slug' => $quiz->slug, 'attempt' => $attempt->id]))
            ->assertNotFound();
    }

    public function test_submit_action_redirects_to_result(): void
    {
        $quiz = $this->publishedQuiz();
        $attempt = app(QuizAttemptService::class)->start($quiz);

        Livewire::test('pages.public.take-quiz', ['slug' => $quiz->slug, 'attempt' => $attempt->id])
            ->call('submit')
            ->assertRedirect(route('quizzes.result', ['slug' => $quiz->slug, 'attempt' => $attempt->id]));
    }

    private function publishedQuiz(): Quiz
    {
        $quiz = Quiz::factory()->create(['status' => 'published']);

        $q1 = $quiz->questions()->create([
            'question' => 'First question?',
            'type' => QuestionType::SingleChoice->value,
            'points' => 5,
            'order' => 1,
        ]);
        $q1->answerOptions()->createMany([
            ['option_text' => 'Correct A', 'is_correct' => true, 'order' => 1],
            ['option_text' => 'Wrong A', 'is_correct' => false, 'order' => 2],
        ]);

        $q2 = $quiz->questions()->create([
            'question' => 'Second question?',
            'type' => QuestionType::SingleChoice->value,
            'points' => 3,
            'order' => 2,
        ]);
        $q2->answerOptions()->createMany([
            ['option_text' => 'Wrong B', 'is_correct' => false, 'order' => 1],
            ['option_text' => 'Correct B', 'is_correct' => true, 'order' => 2],
        ]);

        return $quiz;
    }
}
