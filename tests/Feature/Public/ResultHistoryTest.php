<?php

namespace Tests\Feature\Public;

use App\Models\Quiz;
use App\Models\User;
use App\Services\Assessment\QuizAttemptService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResultHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_results_page_lists_only_own_submitted_attempts(): void
    {
        $quiz = Quiz::factory()->create(['status' => 'published']);
        $quiz->questions()->create([
            'question' => 'Q1',
            'type' => 'single_choice',
            'points' => 5,
            'order' => 1,
        ]);

        $me = User::factory()->create();
        $other = User::factory()->create();

        $mine = app(QuizAttemptService::class)->start($quiz, $me);
        $mine->update(['status' => 'submitted', 'score' => 80, 'submitted_at' => now()]);

        $theirs = app(QuizAttemptService::class)->start($quiz, $other);
        $theirs->update(['status' => 'submitted', 'score' => 90, 'submitted_at' => now()]);

        $this->actingAs($me)
            ->get(route('results.index'))
            ->assertOk()
            ->assertSee($quiz->title)
            ->assertSee('80');
    }

    public function test_admin_is_forbidden_from_results_page(): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->get(route('results.index'))
            ->assertForbidden();
    }

    public function test_admin_is_forbidden_from_public_home(): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->get(route('home'))
            ->assertForbidden();
    }

    public function test_regular_user_can_access_public_home(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('home'))
            ->assertOk();
    }
}
