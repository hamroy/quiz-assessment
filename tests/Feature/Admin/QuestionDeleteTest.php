<?php

namespace Tests\Feature\Admin;

use App\Enums\QuestionType;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class QuestionDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_delete_question(): void
    {
        $user = User::factory()->admin()->create();
        $question = $this->createQuestion();

        Livewire::actingAs($user)
            ->test('pages.admin.question.index', ['quiz' => $question->quiz])
            ->call('delete', $question->id);

        $this->assertDatabaseMissing('questions', ['id' => $question->id]);
    }

    public function test_delete_does_not_affect_other_questions(): void
    {
        $user = User::factory()->admin()->create();
        $quiz = Quiz::factory()->create();

        $q1 = $quiz->questions()->create([
            'question' => 'Q1',
            'type' => QuestionType::SingleChoice->value,
            'points' => 1,
            'order' => 1,
        ]);
        $q2 = $quiz->questions()->create([
            'question' => 'Q2',
            'type' => QuestionType::SingleChoice->value,
            'points' => 1,
            'order' => 2,
        ]);

        Livewire::actingAs($user)
            ->test('pages.admin.question.index', ['quiz' => $quiz])
            ->call('delete', $q1->id);

        $this->assertDatabaseMissing('questions', ['id' => $q1->id]);
        $this->assertDatabaseHas('questions', ['id' => $q2->id]);
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
