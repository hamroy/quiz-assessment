<?php

namespace Tests\Feature\Public;

use App\Enums\QuestionType;
use App\Models\Quiz;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuizAttemptTest extends TestCase
{
    use RefreshDatabase;

    public function test_start_creates_in_progress_attempt_and_redirects(): void
    {
        $quiz = $this->publishedQuiz();

        Livewire::test('pages.public.quiz-detail', ['slug' => $quiz->slug])
            ->call('start')
            ->assertRedirect();

        $this->assertDatabaseHas('quiz_attempts', [
            'quiz_id' => $quiz->id,
            'status' => 'in_progress',
            'user_id' => null,
        ]);
    }

    public function test_draft_quiz_cannot_be_started(): void
    {
        $quiz = Quiz::factory()->create(['status' => 'draft']);

        $this->expectException(\DomainException::class);

        app(\App\Services\Assessment\QuizAttemptService::class)->start($quiz);
    }

    public function test_take_page_renders_first_question(): void
    {
        $quiz = $this->publishedQuiz();
        $attempt = app(\App\Services\Assessment\QuizAttemptService::class)->start($quiz);

        $this->get(route('quizzes.take', ['slug' => $quiz->slug, 'attempt' => $attempt->id]))
            ->assertOk()
            ->assertSee('Question 1 of 2')
            ->assertSee($quiz->questions()->first()->question);
    }

    public function test_answer_selection_persists_correctness_server_side(): void
    {
        $quiz = $this->publishedQuiz();
        $question = $quiz->questions()->first();
        $correct = $question->answerOptions()->where('is_correct', true)->first();
        $wrong = $question->answerOptions()->where('is_correct', false)->first();

        $attempt = app(\App\Services\Assessment\QuizAttemptService::class)->start($quiz);

        Livewire::test('pages.public.take-quiz', ['slug' => $quiz->slug, 'attempt' => $attempt->id])
            ->call('selectOption', $question->id, $correct->id);

        $this->assertDatabaseHas('quiz_answers', [
            'quiz_attempt_id' => $attempt->id,
            'question_id' => $question->id,
            'answer_option_id' => $correct->id,
            'is_correct' => true,
            'points' => $question->points,
        ]);

        Livewire::test('pages.public.take-quiz', ['slug' => $quiz->slug, 'attempt' => $attempt->id])
            ->call('selectOption', $question->id, $wrong->id);

        $this->assertDatabaseHas('quiz_answers', [
            'quiz_attempt_id' => $attempt->id,
            'question_id' => $question->id,
            'answer_option_id' => $wrong->id,
            'is_correct' => false,
            'points' => 0,
        ]);
    }

    public function test_submitted_attempt_returns_404(): void
    {
        $quiz = $this->publishedQuiz();
        $attempt = app(\App\Services\Assessment\QuizAttemptService::class)->start($quiz);
        $attempt->update(['status' => 'submitted']);

        $this->get(route('quizzes.take', ['slug' => $quiz->slug, 'attempt' => $attempt->id]))
            ->assertNotFound();
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
