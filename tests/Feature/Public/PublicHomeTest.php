<?php

namespace Tests\Feature\Public;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicHomeTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_public_home(): void
    {
        $this->get(route('home'))->assertOk();
    }

    public function test_authenticated_user_can_view_public_home_without_admin_navigation(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('home'))
            ->assertOk()
            ->assertSee(config('app.name'))
            ->assertSee(__('Log Out'))
            ->assertDontSee(__('Dashboard'));
    }
}
