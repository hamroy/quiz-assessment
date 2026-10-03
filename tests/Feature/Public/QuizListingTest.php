<?php

namespace Tests\Feature\Public;

use App\Models\Quiz;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class QuizListingTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_published_quizzes_are_listed(): void
    {
        $published = Quiz::factory()->create([
            'title' => 'Stress Assessment',
            'status' => 'published',
        ]);
        Quiz::factory()->create(['title' => 'Hidden Draft', 'status' => 'draft']);
        Quiz::factory()->create(['title' => 'Hidden Archived', 'status' => 'archived']);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Stress Assessment')
            ->assertDontSee('Hidden Draft')
            ->assertDontSee('Hidden Archived');
    }

    public function test_question_count_is_displayed(): void
    {
        $quiz = Quiz::factory()->create(['status' => 'published']);
        $quiz->questions()->createMany([
            ['question' => 'Q1', 'type' => 'single_choice', 'points' => 1, 'order' => 1],
            ['question' => 'Q2', 'type' => 'single_choice', 'points' => 1, 'order' => 2],
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('2 Questions');
    }

    public function test_empty_state_is_shown_when_no_published_quizzes(): void
    {
        Quiz::factory()->create(['status' => 'draft']);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('No assessments available yet.');
    }

    public function test_listing_does_not_trigger_n_plus_one(): void
    {
        Quiz::factory()->count(3)->create(['status' => 'published']);

        $quizQueries = [];
        DB::listen(function ($query) use (&$quizQueries): void {
            if (str_contains(strtolower($query->sql), 'quizzes')) {
                $quizQueries[] = $query->sql;
            }
        });

        $this->get(route('home'))->assertOk();

        $this->assertLessThanOrEqual(2, count($quizQueries), 'Listing must not run a query per row. Got: '.implode(' | ', $quizQueries));
    }
}
