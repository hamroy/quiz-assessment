<?php

namespace Tests\Feature\Admin;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class QuizDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_delete_quiz_without_attempts(): void
    {
        $user = User::factory()->create();
        $quiz = Quiz::factory()->create(['title' => 'To Delete']);

        Livewire::actingAs($user)
            ->test('pages.admin.quiz.index')
            ->call('confirmDelete', $quiz->id)
            ->call('delete')
            ->assertSet('deletingId', null);

        $this->assertDatabaseMissing('quizzes', ['id' => $quiz->id]);
    }

    public function test_delete_is_blocked_when_quiz_has_attempts(): void
    {
        $user = User::factory()->create();
        $quiz = Quiz::factory()->create(['title' => 'Protected Quiz']);

        QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'status' => 'in_progress',
            'score' => 0,
        ]);

        Livewire::actingAs($user)
            ->test('pages.admin.quiz.index')
            ->call('confirmDelete', $quiz->id)
            ->call('delete')
            ->assertSet('deleteError', 'Quiz with attempts cannot be deleted.');

        $this->assertDatabaseHas('quizzes', ['id' => $quiz->id]);
    }

    public function test_delete_requires_confirmation(): void
    {
        $user = User::factory()->create();
        $quiz = Quiz::factory()->create();

        Livewire::actingAs($user)
            ->test('pages.admin.quiz.index')
            ->call('delete')
            ->assertSet('deletingId', null);

        $this->assertDatabaseHas('quizzes', ['id' => $quiz->id]);
    }
}
