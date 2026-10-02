<?php

namespace Tests\Feature\Admin;

use App\Models\Quiz;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class QuizPublishTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_publish_quiz_with_questions(): void
    {
        $user = User::factory()->create();
        $quiz = Quiz::factory()->create(['status' => 'draft', 'published_at' => null]);
        $quiz->questions()->create([
            'question' => 'Question 1',
            'type' => 'single_choice',
            'points' => 1,
            'order' => 1,
        ]);

        Livewire::actingAs($user)
            ->test('pages.admin.quiz.index')
            ->call('publish', $quiz->id);

        $this->assertDatabaseHas('quizzes', [
            'id' => $quiz->id,
            'status' => 'published',
        ]);
        $this->assertNotNull($quiz->fresh()->published_at);
    }

    public function test_publish_is_blocked_when_quiz_has_no_questions(): void
    {
        $user = User::factory()->create();
        $quiz = Quiz::factory()->create(['status' => 'draft', 'published_at' => null]);

        Livewire::actingAs($user)
            ->test('pages.admin.quiz.index')
            ->call('publish', $quiz->id)
            ->assertSet('publishError', 'Quiz must have at least one question to be published.');

        $this->assertDatabaseHas('quizzes', [
            'id' => $quiz->id,
            'status' => 'draft',
            'published_at' => null,
        ]);
    }

    public function test_admin_can_archive_published_quiz_and_retain_published_at(): void
    {
        $user = User::factory()->create();
        $publishedAt = now()->subDay();
        $quiz = Quiz::factory()->create([
            'status' => 'published',
            'published_at' => $publishedAt,
        ]);

        Livewire::actingAs($user)
            ->test('pages.admin.quiz.index')
            ->call('archive', $quiz->id);

        $this->assertDatabaseHas('quizzes', [
            'id' => $quiz->id,
            'status' => 'archived',
            'published_at' => $publishedAt->toDateTimeString(),
        ]);
    }

    public function test_admin_can_republish_archived_quiz(): void
    {
        $user = User::factory()->create();
        $quiz = Quiz::factory()->create(['status' => 'archived']);
        $quiz->questions()->create([
            'question' => 'Question 1',
            'type' => 'single_choice',
            'points' => 1,
            'order' => 1,
        ]);

        Livewire::actingAs($user)
            ->test('pages.admin.quiz.index')
            ->call('publish', $quiz->id);

        $this->assertDatabaseHas('quizzes', [
            'id' => $quiz->id,
            'status' => 'published',
        ]);
        $this->assertNotNull($quiz->fresh()->published_at);
    }
}
