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
        $this->get(route('home'))
            ->assertOk()
            ->assertSee(config('app.name'))
            ->assertSee(__('Log in'));
    }

    public function test_authenticated_user_sees_dashboard_link(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('home'))
            ->assertOk()
            ->assertSee(__('Dashboard'))
            ->assertDontSee(__('Log in'));
    }
}
