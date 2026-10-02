<?php

namespace Tests\Feature\Admin;

use App\Models\Quiz;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class QuizEditTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $quiz = Quiz::factory()->create();

        $this->get("/admin/quizzes/{$quiz->id}/edit")
            ->assertRedirect(route('login'));
    }

    public function test_admin_can_view_edit_form_with_existing_values(): void
    {
        $quiz = Quiz::factory()->create([
            'title' => 'Existing Quiz',
            'description' => 'Existing description',
            'status' => 'draft',
        ]);

        $this->actingAs(User::factory()->create())
            ->get("/admin/quizzes/{$quiz->id}/edit")
            ->assertStatus(200)
            ->assertSee('Edit Quiz')
            ->assertSee('Existing Quiz')
            ->assertSee('Existing description');
    }

    public function test_admin_can_update_quiz(): void
    {
        $user = User::factory()->create();
        $quiz = Quiz::factory()->create([
            'title' => 'Old Title',
            'slug' => 'old-title',
            'status' => 'draft',
        ]);

        Livewire::actingAs($user)
            ->test('pages.admin.quiz.edit', ['quiz' => $quiz->id])
            ->set('title', 'New Title')
            ->set('description', 'New description')
            ->set('status', 'published')
            ->call('save')
            ->assertRedirect(route('admin.quizzes.index'));

        $this->assertDatabaseHas('quizzes', [
            'id' => $quiz->id,
            'title' => 'New Title',
            'slug' => 'new-title',
            'description' => 'New description',
            'status' => 'published',
        ]);
    }

    public function test_updating_title_to_duplicate_is_rejected(): void
    {
        $user = User::factory()->create();

        $quiz = Quiz::factory()->create(['title' => 'Alpha', 'slug' => 'alpha']);
        Quiz::factory()->create(['title' => 'Beta', 'slug' => 'beta']);

        Livewire::actingAs($user)
            ->test('pages.admin.quiz.edit', ['quiz' => $quiz->id])
            ->set('title', 'Beta')
            ->call('save')
            ->assertHasErrors(['title' => 'unique']);

        $this->assertDatabaseHas('quizzes', [
            'id' => $quiz->id,
            'title' => 'Alpha',
        ]);
    }

    public function test_editing_without_changing_title_keeps_slug(): void
    {
        $user = User::factory()->create();
        $quiz = Quiz::factory()->create(['title' => 'Stable', 'slug' => 'stable']);

        Livewire::actingAs($user)
            ->test('pages.admin.quiz.edit', ['quiz' => $quiz->id])
            ->set('title', 'Stable')
            ->set('status', 'archived')
            ->call('save');

        $this->assertDatabaseHas('quizzes', [
            'id' => $quiz->id,
            'slug' => 'stable',
            'status' => 'archived',
        ]);
    }

    public function test_title_is_required(): void
    {
        $quiz = Quiz::factory()->create();

        Livewire::actingAs(User::factory()->create())
            ->test('pages.admin.quiz.edit', ['quiz' => $quiz->id])
            ->set('title', '')
            ->call('save')
            ->assertHasErrors(['title' => 'required']);
    }
}
