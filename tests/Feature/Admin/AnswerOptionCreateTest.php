<?php

namespace Tests\Feature\Admin;

use App\Enums\QuestionType;
use App\Models\AnswerOption;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AnswerOptionCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $question = $this->createQuestion();

        $this->get(route('admin.answer-options.create', ['quiz' => $question->quiz_id, 'question' => $question->id]))
            ->assertRedirect(route('login'));
    }

    public function test_admin_can_view_create_form(): void
    {
        $question = $this->createQuestion();

        $this->actingAs(User::factory()->create())
            ->get(route('admin.answer-options.create', ['quiz' => $question->quiz_id, 'question' => $question->id]))
            ->assertOk()
            ->assertSee('Add Answer Option')
            ->assertSee('Option Text')
            ->assertSee('Correct Answer')
            ->assertSee('Order');
    }

    public function test_admin_can_create_answer_option(): void
    {
        $user = User::factory()->create();
        $question = $this->createQuestion();

        Livewire::actingAs($user)
            ->test('pages.admin.answer-option.create', ['quiz' => $question->quiz_id, 'question' => $question->id])
            ->set('option_text', 'Option A')
            ->set('is_correct', true)
            ->set('order', 1)
            ->call('save')
            ->assertRedirect(route('admin.questions.edit', ['quiz' => $question->quiz_id, 'question' => $question->id]));

        $this->assertDatabaseHas('answer_options', [
            'question_id' => $question->id,
            'option_text' => 'Option A',
            'is_correct' => true,
            'order' => 1,
        ]);
    }

    public function test_option_text_is_required(): void
    {
        $user = User::factory()->create();
        $question = $this->createQuestion();

        Livewire::actingAs($user)
            ->test('pages.admin.answer-option.create', ['quiz' => $question->quiz_id, 'question' => $question->id])
            ->set('option_text', '')
            ->set('is_correct', false)
            ->set('order', 1)
            ->call('save')
            ->assertHasErrors(['option_text' => 'required']);
    }

    public function test_order_is_prefilled_with_next_position(): void
    {
        $question = $this->createQuestion();

        $question->answerOptions()->create(['option_text' => 'A', 'is_correct' => false, 'order' => 1]);
        $question->answerOptions()->create(['option_text' => 'B', 'is_correct' => false, 'order' => 2]);

        $service = app(\App\Services\AnswerOption\AnswerOptionService::class);
        $this->assertSame(3, $service->nextOrder($question));
    }

    public function test_is_correct_defaults_to_false(): void
    {
        $user = User::factory()->create();
        $question = $this->createQuestion();

        Livewire::actingAs($user)
            ->test('pages.admin.answer-option.create', ['quiz' => $question->quiz_id, 'question' => $question->id])
            ->set('option_text', 'Wrong option')
            ->set('order', 1)
            ->call('save');

        $this->assertDatabaseHas('answer_options', [
            'question_id' => $question->id,
            'option_text' => 'Wrong option',
            'is_correct' => false,
        ]);
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
