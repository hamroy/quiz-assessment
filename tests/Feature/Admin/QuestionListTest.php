<?php

namespace Tests\Feature\Admin;

use App\Models\Quiz;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class QuestionListTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $quiz = Quiz::factory()->create();

        $this->get(route('admin.questions.index', $quiz))
            ->assertRedirect(route('login'));
    }

    public function test_admin_can_view_questions_for_a_quiz(): void
    {
        $user = User::factory()->admin()->create();
        $quiz = Quiz::factory()->create(['title' => 'Sample Quiz']);
        $question = $quiz->questions()->create([
            'question' => 'What is the answer?',
            'type' => 'single_choice',
            'points' => 5,
            'order' => 2,
        ]);
        $question->answerOptions()->createMany([
            ['option_text' => 'Option A', 'is_correct' => false, 'order' => 1],
            ['option_text' => 'Option B', 'is_correct' => true, 'order' => 2],
        ]);

        $this->actingAs($user)
            ->get(route('admin.questions.index', $quiz))
            ->assertOk()
            ->assertSee('Sample Quiz')
            ->assertSee('What is the answer?')
            ->assertSee('Single choice')
            ->assertSee('Points')
            ->assertSee('Order')
            ->assertSee('Options')
            ->assertSee('2');
    }

    public function test_question_list_is_scoped_to_the_selected_quiz(): void
    {
        $user = User::factory()->admin()->create();
        $quiz = Quiz::factory()->create();
        $otherQuiz = Quiz::factory()->create();
        $quiz->questions()->create([
            'question' => 'Included question',
            'type' => 'single_choice',
            'points' => 1,
            'order' => 1,
        ]);
        $otherQuiz->questions()->create([
            'question' => 'Excluded question',
            'type' => 'single_choice',
            'points' => 1,
            'order' => 1,
        ]);

        $this->actingAs($user)
            ->get(route('admin.questions.index', $quiz))
            ->assertOk()
            ->assertSee('Included question')
            ->assertDontSee('Excluded question');
    }

    public function test_question_list_does_not_trigger_n_plus_one_queries(): void
    {
        $user = User::factory()->admin()->create();
        $quiz = Quiz::factory()->create();
        $questions = $quiz->questions()->createMany(collect(range(1, 3))->map(fn ($number) => [
            'question' => 'Question '.$number,
            'type' => 'single_choice',
            'points' => 1,
            'order' => $number,
        ])->all());

        foreach ($questions as $question) {
            $question->answerOptions()->create([
                'option_text' => 'Option',
                'is_correct' => false,
                'order' => 1,
            ]);
        }

        $questionQueries = [];
        DB::listen(function ($query) use (&$questionQueries): void {
            if (str_contains(strtolower($query->sql), 'questions') || str_contains(strtolower($query->sql), 'answer_options')) {
                $questionQueries[] = $query->sql;
            }
        });

        $this->actingAs($user)
            ->get(route('admin.questions.index', $quiz))
            ->assertOk();

        $this->assertLessThanOrEqual(3, count($questionQueries), 'Question list must not query per question. Got: '.implode(' | ', $questionQueries));
    }

    public function test_question_list_shows_empty_state(): void
    {
        $user = User::factory()->admin()->create();
        $quiz = Quiz::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.questions.index', $quiz))
            ->assertOk()
            ->assertSee('No questions yet.');
    }
}
