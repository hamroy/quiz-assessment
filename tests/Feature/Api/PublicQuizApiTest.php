<?php

namespace Tests\Feature\Api;

use App\Enums\QuizStatus;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicQuizApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_lists_only_published_quizzes(): void
    {
        $published = Quiz::factory()->create(['status' => QuizStatus::Published->value]);
        Quiz::factory()->create(['status' => QuizStatus::Draft->value]);
        Quiz::factory()->create(['status' => QuizStatus::Archived->value]);

        $response = $this->getJson('/api/v1/quizzes');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', $published->slug)
            ->assertJsonPath('meta.total', 1);
    }

    public function test_it_requires_no_authentication(): void
    {
        Quiz::factory()->create(['status' => QuizStatus::Published->value]);

        $this->getJson('/api/v1/quizzes')->assertOk();
    }

    public function test_it_filters_by_type(): void
    {
        $mbti = Quiz::factory()->create([
            'status' => QuizStatus::Published->value,
            'type' => 'mbti',
        ]);
        Quiz::factory()->create([
            'status' => QuizStatus::Published->value,
            'type' => 'general',
        ]);

        $response = $this->getJson('/api/v1/quizzes?type=mbti');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', $mbti->slug)
            ->assertJsonPath('data.0.type_label', 'MBTI');
    }

    public function test_it_rejects_an_unknown_type(): void
    {
        $this->getJson('/api/v1/quizzes?type=nope')->assertStatus(422);
    }

    public function test_it_honours_per_page_within_bounds(): void
    {
        Quiz::factory()->count(3)->create(['status' => QuizStatus::Published->value]);

        $this->getJson('/api/v1/quizzes?per_page=2')
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('meta.per_page', 2);

        $this->getJson('/api/v1/quizzes?per_page=0')->assertStatus(422);
        $this->getJson('/api/v1/quizzes?per_page=999')->assertStatus(422);
    }

    public function test_it_returns_the_quiz_detail(): void
    {
        $quiz = Quiz::factory()->create(['status' => QuizStatus::Published->value]);
        $question = $quiz->questions()->create(['question' => 'What is 2 + 2?', 'points' => 1]);
        $question->answerOptions()->create(['option_text' => '4', 'is_correct' => true]);

        $response = $this->getJson("/api/v1/quizzes/{$quiz->slug}");

        $response->assertOk()
            ->assertJsonPath('data.slug', $quiz->slug)
            ->assertJsonPath('data.questions_count', 1)
            ->assertJsonMissing(['is_correct' => true]);
    }

    public function test_it_never_exposes_draft_quizzes_or_answers(): void
    {
        $draft = Quiz::factory()->create(['status' => QuizStatus::Draft->value]);
        $question = $draft->questions()->create(['question' => 'Secret question?', 'points' => 1]);
        $question->answerOptions()->create(['option_text' => 'Secret option', 'is_correct' => true]);

        $response = $this->getJson("/api/v1/quizzes/{$draft->slug}");

        $response->assertNotFound()
            ->assertJsonMissing(['is_correct' => true]);
    }

    public function test_it_returns_404_for_an_unknown_slug(): void
    {
        $this->getJson('/api/v1/quizzes/does-not-exist')->assertNotFound();
    }

    public function test_it_serves_the_openapi_specification_and_docs_ui(): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->get('/docs/api')
            ->assertOk()
            ->assertSee('Quiz Assessment API');

        $spec = $this->actingAs(User::factory()->admin()->create())
            ->get('/docs/api.json')->assertOk()->json();

        $this->assertSame('3.1.0', $spec['openapi']);
        $this->assertSame('Quiz Assessment API', $spec['info']['title']);
        $this->assertArrayHasKey('/v1/quizzes', $spec['paths']);
        $this->assertArrayHasKey('/v1/quizzes/{slug}', $spec['paths']);
        $this->assertArrayHasKey('QuizResource', $spec['components']['schemas']);
        $this->assertArrayHasKey('QuizType', $spec['components']['schemas']);
    }

    public function test_the_docs_are_forbidden_outside_local_for_non_admins(): void
    {
        $this->get('/docs/api')->assertForbidden();
        $this->get('/docs/api.json')->assertForbidden();

        $this->actingAs(User::factory()->create())
            ->get('/docs/api.json')
            ->assertForbidden();
    }

    public function test_the_specification_does_not_leak_draft_quizzes_or_answers(): void
    {
        $spec = $this->actingAs(User::factory()->admin()->create())
            ->get('/docs/api.json')->assertOk()->json();

        $properties = array_keys($spec['components']['schemas']['QuizResource']['properties']);

        $this->assertNotContains('is_correct', $properties);
        $this->assertNotContains('status', $properties);
    }
}
