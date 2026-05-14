<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResponseIntegrityTest extends TestCase
{
    use RefreshDatabase;

    public function test_root_redirects_to_onboarding()
    {
        $response = $this->get('/');
        $response->assertRedirect(route('onboarding.welcome'));
    }

    public function test_onboarding_welcome_page_loads()
    {
        $response = $this->get(route('onboarding.welcome'));
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Onboarding/Welcome'));
    }

    public function test_onboarding_step_loads()
    {
        $response = $this->get(route('onboarding.step', ['step' => 2]));
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Onboarding/Language'));
    }

    public function test_authenticated_user_accesses_onboarding()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('onboarding.welcome'));
        $response->assertStatus(200);
    }
}
