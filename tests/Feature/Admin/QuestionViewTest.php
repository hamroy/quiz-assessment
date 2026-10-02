<?php

namespace Tests\Feature\Admin;

use App\Enums\QuestionType;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuestionViewTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $question = $this->createQuestion();

        $this->get(route('admin.questions.view', ['quiz' => $question->quiz_id, 'question' => $question->id]))
            ->assertRedirect(route('login'));
    }

    public function test_admin_can_view_question_with_answer_options(): void
    {
        $question = $this->createQuestion();
        $question->answerOptions()->create(['option_text' => 'Correct one', 'is_correct' => true, 'order' => 1]);
        $question->answerOptions()->create(['option_text' => 'Wrong one', 'is_correct' => false, 'order' => 2]);

        $this->actingAs(User::factory()->create())
            ->get(route('admin.questions.view', ['quiz' => $question->quiz_id, 'question' => $question->id]))
            ->assertOk()
            ->assertSee('View Question')
            ->assertSee('Correct one')
            ->assertSee('Wrong one')
            ->assertSee('Correct');
    }

    public function test_view_shows_empty_state_when_no_options(): void
    {
        $question = $this->createQuestion();

        $this->actingAs(User::factory()->create())
            ->get(route('admin.questions.view', ['quiz' => $question->quiz_id, 'question' => $question->id]))
            ->assertOk()
            ->assertSee('No answer options yet.');
    }

    private function createQuestion(): Question
    {
        $quiz = Quiz::factory()->create();

        return $quiz->questions()->create([
            'question' => 'Test question?',
            'type' => QuestionType::SingleChoice->value,
            'points' => 1,
            'order' => 1,
        ]);
    }
}
