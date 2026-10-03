<?php

namespace Tests\Feature\Admin;

use App\Models\Quiz;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class QuizCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $this->get('/admin/quizzes/create')
            ->assertRedirect(route('login'));
    }

    public function test_admin_can_view_create_form(): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->get('/admin/quizzes/create')
            ->assertStatus(200)
            ->assertSee('Create Quiz');
    }

    public function test_admin_can_create_quiz(): void
    {
        $user = User::factory()->admin()->create();

        Livewire::actingAs($user)
            ->test('pages.admin.quiz.create')
            ->set('form.title', 'My First Quiz')
            ->set('form.description', 'A sample quiz')
            ->set('form.status', 'draft')
            ->call('save')
            ->assertRedirect(route('admin.quizzes.index'));

        $this->assertDatabaseHas('quizzes', [
            'title' => 'My First Quiz',
            'slug' => 'my-first-quiz',
            'status' => 'draft',
        ]);
    }

    public function test_title_is_required(): void
    {
        Livewire::actingAs(User::factory()->admin()->create())
            ->test('pages.admin.quiz.create')
            ->set('form.title', '')
            ->call('save')
            ->assertHasErrors(['form.title' => 'required']);
    }

    public function test_status_must_be_valid(): void
    {
        Livewire::actingAs(User::factory()->admin()->create())
            ->test('pages.admin.quiz.create')
            ->set('form.title', 'Valid Title')
            ->set('form.status', 'invalid')
            ->call('save')
            ->assertHasErrors(['form.status']);
    }

    public function test_duplicate_title_is_rejected(): void
    {
        $user = User::factory()->admin()->create();

        Quiz::factory()->create(['title' => 'Same Title', 'slug' => 'same-title']);

        Livewire::actingAs($user)
            ->test('pages.admin.quiz.create')
            ->set('form.title', 'Same Title')
            ->set('form.status', 'draft')
            ->call('save')
            ->assertHasErrors(['form.title' => 'unique']);

        $this->assertSame(1, Quiz::query()->where('title', 'Same Title')->count());
    }
}
