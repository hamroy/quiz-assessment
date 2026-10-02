<?php

namespace Tests\Feature\Admin;

use App\Enums\QuestionType;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class QuestionEditTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $question = $this->createQuestion();

        $this->get(route('admin.questions.edit', ['quiz' => $question->quiz, 'question' => $question]))
            ->assertRedirect(route('login'));
    }

    public function test_admin_can_view_edit_form_with_existing_values(): void
    {
        $question = $this->createQuestion([
            'question' => 'Original question?',
            'points' => 3,
            'order' => 2,
        ]);

        $this->actingAs(User::factory()->create())
            ->get(route('admin.questions.edit', ['quiz' => $question->quiz, 'question' => $question]))
            ->assertOk()
            ->assertSee('Edit Question');
    }

    public function test_admin_can_update_question(): void
    {
        $user = User::factory()->create();
        $question = $this->createQuestion();

        Livewire::actingAs($user)
            ->test('pages.admin.question.edit', ['quiz' => $question->quiz->id, 'question' => $question->id])
            ->set('question', 'Updated question?')
            ->set('type', QuestionType::SingleChoice->value)
            ->set('points', 5)
            ->set('order', 1)
            ->call('save')
            ->assertRedirect(route('admin.questions.index', ['quiz' => $question->quiz->id]));

        $this->assertDatabaseHas('questions', [
            'id' => $question->id,
            'question' => 'Updated question?',
            'points' => 5,
            'order' => 1,
        ]);
    }

    public function test_question_text_is_required(): void
    {
        $question = $this->createQuestion();

        Livewire::actingAs(User::factory()->create())
            ->test('pages.admin.question.edit', ['quiz' => $question->quiz->id, 'question' => $question->id])
            ->set('question', '')
            ->call('save')
            ->assertHasErrors(['question' => 'required']);
    }

    public function test_points_must_be_at_least_1(): void
    {
        $question = $this->createQuestion();

        Livewire::actingAs(User::factory()->create())
            ->test('pages.admin.question.edit', ['quiz' => $question->quiz->id, 'question' => $question->id])
            ->set('question', 'Test')
            ->set('points', 0)
            ->call('save')
            ->assertHasErrors(['points' => 'min']);
    }

    public function test_type_must_be_valid_enum(): void
    {
        $question = $this->createQuestion();

        Livewire::actingAs(User::factory()->create())
            ->test('pages.admin.question.edit', ['quiz' => $question->quiz->id, 'question' => $question->id])
            ->set('question', 'Test')
            ->set('type', 'invalid')
            ->call('save')
            ->assertHasErrors(['type']);
    }

    private function createQuestion(array $overrides = []): Question
    {
        $quiz = Quiz::factory()->create();

        return $quiz->questions()->create(array_merge([
            'question' => 'Test question?',
            'type' => QuestionType::SingleChoice->value,
            'points' => 1,
            'order' => 1,
        ], $overrides));
    }
}
