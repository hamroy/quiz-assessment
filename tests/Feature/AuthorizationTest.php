<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login_from_admin_pages(): void
    {
        $this->get('/admin/quizzes')->assertRedirect(route('login'));
        $this->get('/dashboard')->assertRedirect(route('login'));
    }

    public function test_guests_can_view_public_home(): void
    {
        $this->get(route('home'))->assertOk();
    }

    public function test_regular_users_can_view_public_home_but_cannot_access_admin_or_dashboard(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('home'))->assertOk();
        $this->actingAs($user)->get('/admin/quizzes')->assertForbidden();
        $this->actingAs($user)->get('/dashboard')->assertForbidden();
    }

    public function test_admin_can_access_admin_pages_and_dashboard(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get('/admin/quizzes')->assertOk();
        $this->actingAs($admin)->get('/dashboard')->assertOk();
    }

    public function test_user_login_redirects_to_public_home(): void
    {
        $user = User::factory()->create();

        Volt::test('pages.auth.login')
            ->set('form.email', $user->email)
            ->set('form.password', 'password')
            ->call('login')
            ->assertRedirect(route('home', absolute: false));
    }

    public function test_admin_login_redirects_to_dashboard(): void
    {
        $admin = User::factory()->admin()->create();

        Volt::test('pages.auth.login')
            ->set('form.email', $admin->email)
            ->set('form.password', 'password')
            ->call('login')
            ->assertRedirect(route('dashboard', absolute: false));
    }
}
