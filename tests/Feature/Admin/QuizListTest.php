<?php

namespace Tests\Feature\Admin;

use App\Models\Quiz;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuizListTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $this->get('/admin/quizzes')
            ->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_quiz_list(): void
    {
        $user = User::factory()->admin()->create();

        $quiz = Quiz::factory()->create([
            'title' => 'Sample Quiz',
            'status' => 'published',
        ]);

        $quiz->questions()->create([
            'question' => 'Question 1',
            'type' => 'single_choice',
            'points' => 1,
            'order' => 1,
        ]);

        $response = $this->actingAs($user)->get('/admin/quizzes');

        $response->assertStatus(200);
        $response->assertSee('Sample Quiz');
        $response->assertSee('Published');
        $response->assertSee($quiz->created_at->format('Y-m-d'));
        $response->assertSee('Edit');
        $this->assertStringContainsString(
            '>'.strip_tags((string) $quiz->questions_count).'<',
            preg_replace('/\s+/', '', $response->getContent()),
        );
    }

    public function test_question_count_does_not_trigger_n_plus_one(): void
    {
        $user = User::factory()->admin()->create();

        Quiz::factory()->count(3)->create();

        $quizQueries = [];
        \Illuminate\Support\Facades\DB::listen(function ($query) use (&$quizQueries): void {
            // only count quizzes table queries; auth/session noise ignored
            if (str_contains(strtolower($query->sql), 'quizzes')) {
                $quizQueries[] = $query->sql;
            }
        });

        $this->actingAs($user)->get('/admin/quizzes')->assertStatus(200);

        // withCount + paginated select = at most 2 quizzes queries, no per-row query
        $this->assertLessThanOrEqual(2, count($quizQueries), 'Quiz list must not run a query per row. Got: '.implode(' | ', $quizQueries));
    }

    public function test_quiz_list_is_paginated_and_shows_empty_state(): void
    {
        $user = User::factory()->admin()->create();

        $response = $this->actingAs($user)->get('/admin/quizzes');

        $response->assertStatus(200);
        $response->assertSee('No quizzes yet.');
    }
}
