<?php

namespace Tests\Feature\Admin;

use App\Enums\QuestionType;
use App\Models\Quiz;
use App\Models\User;
use App\Services\Question\QuestionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class QuestionCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $quiz = Quiz::factory()->create();

        $this->get(route('admin.questions.create', $quiz))
            ->assertRedirect(route('login'));
    }

    public function test_admin_can_view_create_question_form(): void
    {
        $user = User::factory()->create();
        $quiz = Quiz::factory()->create(['title' => 'Sample Quiz']);

        $this->actingAs($user)
            ->get(route('admin.questions.create', $quiz))
            ->assertOk()
            ->assertSee('Add Question')
            ->assertSee('Sample Quiz')
            ->assertSee('Question')
            ->assertSee('Type')
            ->assertSee('Points')
            ->assertSee('Order');
    }

    public function test_order_is_prefilled_with_next_position(): void
    {
        $quiz = Quiz::factory()->create();

        $quiz->questions()->create([
            'question' => 'Q1',
            'type' => 'single_choice',
            'points' => 1,
            'order' => 1,
        ]);
        $quiz->questions()->create([
            'question' => 'Q2',
            'type' => 'single_choice',
            'points' => 1,
            'order' => 2,
        ]);

        $this->assertSame(3, app(QuestionService::class)->nextOrder($quiz));
    }

    public function test_admin_can_create_a_question(): void
    {
        $user = User::factory()->create();
        $quiz = Quiz::factory()->create();

        Livewire::actingAs($user)
            ->test('pages.admin.question.create', ['quiz' => $quiz])
            ->set('question', 'What is the capital of France?')
            ->set('type', QuestionType::SingleChoice->value)
            ->set('points', 2)
            ->set('order', 1)
            ->call('save')
            ->assertRedirect(route('admin.questions.index', ['quiz' => $quiz->id]));

        $this->assertDatabaseHas('questions', [
            'quiz_id' => $quiz->id,
            'question' => 'What is the capital of France?',
            'type' => QuestionType::SingleChoice->value,
            'points' => 2,
            'order' => 1,
        ]);
    }

    public function test_question_is_required(): void
    {
        $user = User::factory()->create();
        $quiz = Quiz::factory()->create();

        Livewire::actingAs($user)
            ->test('pages.admin.question.create', ['quiz' => $quiz])
            ->set('question', '')
            ->set('type', QuestionType::SingleChoice->value)
            ->set('points', 1)
            ->set('order', 1)
            ->call('save')
            ->assertHasErrors(['question' => 'required']);
    }

    public function test_points_must_be_at_least_1(): void
    {
        $user = User::factory()->create();
        $quiz = Quiz::factory()->create();

        Livewire::actingAs($user)
            ->test('pages.admin.question.create', ['quiz' => $quiz])
            ->set('question', 'Test question')
            ->set('type', QuestionType::SingleChoice->value)
            ->set('points', 0)
            ->set('order', 1)
            ->call('save')
            ->assertHasErrors(['points' => 'min']);
    }

    public function test_type_must_be_valid_enum(): void
    {
        $user = User::factory()->create();
        $quiz = Quiz::factory()->create();

        Livewire::actingAs($user)
            ->test('pages.admin.question.create', ['quiz' => $quiz])
            ->set('question', 'Test question')
            ->set('type', 'invalid_type')
            ->set('points', 1)
            ->set('order', 1)
            ->call('save')
            ->assertHasErrors(['type']);
    }
}
