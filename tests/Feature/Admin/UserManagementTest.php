<?php

namespace Tests\Feature\Admin;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_edit_and_delete_user(): void
    {
        $admin = User::factory()->admin()->create();
        $this->actingAs($admin);

        Volt::test('pages.admin.user.create')
            ->set('form.name', 'Quiz Taker')
            ->set('form.email', 'taker@example.com')
            ->set('form.password', 'password')
            ->set('form.password_confirmation', 'password')
            ->set('form.role', UserRole::User->value)
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('admin.users.index'));

        $user = User::where('email', 'taker@example.com')->firstOrFail();
        $this->assertSame(UserRole::User, $user->role);
        $this->assertNotNull($user->email_verified_at);

        Volt::test('pages.admin.user.edit', ['user' => $user])
            ->set('form.name', 'Updated Taker')
            ->call('save')
            ->assertHasNoErrors();
        $this->assertSame('Updated Taker', $user->fresh()->name);

        Volt::test('pages.admin.user.index')
            ->call('delete', $user->id)
            ->assertHasNoErrors();
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_admin_cannot_delete_their_own_account(): void
    {
        $admin = User::factory()->admin()->create();
        $this->actingAs($admin);

        Volt::test('pages.admin.user.index')
            ->call('delete', $admin->id)
            ->assertSet('error', 'You cannot delete your own account.');

        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_admin_can_delete_other_admins(): void
    {
        $admin = User::factory()->admin()->create();
        $this->actingAs($admin);

        $secondAdmin = User::factory()->admin()->create();

        Volt::test('pages.admin.user.index')
            ->call('delete', $secondAdmin->id)
            ->assertSet('error', '');

        $this->assertDatabaseHas('users', ['id' => $admin->id]);
        $this->assertDatabaseMissing('users', ['id' => $secondAdmin->id]);
    }

    public function test_regular_user_cannot_access_user_management(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/admin/users')->assertForbidden();
    }
}
