<?php

namespace Tests\Feature\Public;

use App\Models\Quiz;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuizDetailTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
    }

    public function test_published_quiz_detail_is_viewable_by_slug(): void
    {
        $quiz = Quiz::factory()->create([
            'title' => 'Stress Assessment',
            'slug' => 'stress-assessment',
            'status' => 'published',
            'description' => 'A stress assessment.',
        ]);
        $quiz->questions()->create([
            'question' => 'Q1',
            'type' => 'single_choice',
            'points' => 1,
            'order' => 1,
        ]);

        $this->get(route('quizzes.show', $quiz->slug))
            ->assertOk()
            ->assertSee('Stress Assessment')
            ->assertSee('A stress assessment.')
            ->assertSee('1 Questions');
    }

    public function test_draft_quiz_returns_404(): void
    {
        $quiz = Quiz::factory()->create([
            'slug' => 'draft-quiz',
            'status' => 'draft',
        ]);

        $this->get(route('quizzes.show', $quiz->slug))
            ->assertNotFound();
    }

    public function test_archived_quiz_returns_404(): void
    {
        $quiz = Quiz::factory()->create([
            'slug' => 'archived-quiz',
            'status' => 'archived',
        ]);

        $this->get(route('quizzes.show', $quiz->slug))
            ->assertNotFound();
    }

    public function test_unknown_slug_returns_404(): void
    {
        $this->get(route('quizzes.show', 'does-not-exist'))
            ->assertNotFound();
    }
}
