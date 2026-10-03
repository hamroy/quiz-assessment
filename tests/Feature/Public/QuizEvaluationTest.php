<?php

namespace Tests\Feature\Public;

use App\Enums\QuestionType;
use App\Models\Quiz;
use App\Services\Assessment\EvaluationService;
use App\Services\Assessment\QuizAttemptService;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuizEvaluationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
    }

    public function test_evaluation_counts_answers_and_returns_question_details(): void
    {
        [$quiz, $questions] = $this->publishedQuiz();
        $attemptService = app(QuizAttemptService::class);
        $attempt = $attemptService->start($quiz);

        $attemptService->answer($attempt, $questions[0], $questions[0]->answerOptions->firstWhere('is_correct', true));
        $attemptService->answer($attempt, $questions[1], $questions[1]->answerOptions->firstWhere('is_correct', false));

        $result = app(EvaluationService::class)->evaluate($attempt);

        $this->assertSame(1, $result->correct);
        $this->assertSame(1, $result->wrong);
        $this->assertSame(1, $result->unanswered);
        $this->assertSame(3, $result->total);

        $this->assertSame('Question 1?', $result->items[0]->question);
        $this->assertSame('Correct 1', $result->items[0]->selectedText);
        $this->assertSame('Correct 1', $result->items[0]->correctText);
        $this->assertTrue($result->items[0]->isCorrect);
        $this->assertSame(5, $result->items[0]->points);
        $this->assertSame(5, $result->items[0]->maxPoints);

        $this->assertSame('Wrong 2', $result->items[1]->selectedText);
        $this->assertSame('Correct 2', $result->items[1]->correctText);
        $this->assertFalse($result->items[1]->isCorrect);
        $this->assertSame(0, $result->items[1]->points);

        $this->assertFalse($result->items[2]->answered);
        $this->assertNull($result->items[2]->selectedText);
        $this->assertSame(0, $result->items[2]->points);
    }

    public function test_result_page_displays_answer_summary_and_review(): void
    {
        [$quiz, $questions] = $this->publishedQuiz();
        $attemptService = app(QuizAttemptService::class);
        $attempt = $attemptService->start($quiz);

        $attemptService->answer($attempt, $questions[0], $questions[0]->answerOptions->firstWhere('is_correct', true));
        $attemptService->answer($attempt, $questions[1], $questions[1]->answerOptions->firstWhere('is_correct', false));
        $attempt = $attemptService->submit($attempt);

        $this->get(route('quizzes.result', ['slug' => $quiz->slug, 'attempt' => $attempt->id]))
            ->assertOk()
            ->assertSee('Question Review')
            ->assertSee('Correct')
            ->assertSee('Wrong')
            ->assertSee('Unanswered')
            ->assertSee('Correct 1')
            ->assertSee('Wrong 2')
            ->assertSee('Not answered')
            ->assertSee('Points:')
            ->assertSee('5 / 5')
            ->assertSee('0 / 3');
    }

    /** @return array{Quiz, array<int, \App\Models\Question>} */
    private function publishedQuiz(): array
    {
        $quiz = Quiz::factory()->create(['status' => 'published']);
        $questions = [];

        foreach ([5, 3, 2] as $index => $points) {
            $order = $index + 1;
            $question = $quiz->questions()->create([
                'question' => "Question {$order}?",
                'type' => QuestionType::SingleChoice->value,
                'points' => $points,
                'order' => $order,
            ]);
            $question->answerOptions()->createMany([
                ['option_text' => "Correct {$order}", 'is_correct' => true, 'order' => 1],
                ['option_text' => "Wrong {$order}", 'is_correct' => false, 'order' => 2],
            ]);
            $question->load('answerOptions');
            $questions[] = $question;
        }

        return [$quiz, $questions];
    }
}
