<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocalizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_switches_locale_for_guests_via_cookie()
    {
        $response = $this->post(route('locale.switch'), [
            'locale' => 'nl'
        ]);

        $response->assertStatus(302);
        $response->assertCookie('benlocal_locale', 'nl');
    }

    public function test_it_switches_locale_for_authenticated_users()
    {
        $user = User::factory()->create([
            'preferred_language' => 'en'
        ]);

        $response = $this->actingAs($user)->post(route('locale.switch'), [
            'locale' => 'es'
        ]);

        $response->assertStatus(302);
        $this->assertEquals('es', $user->fresh()->preferred_language);
    }

    public function test_it_resolves_browser_locale()
    {
        $response = $this->get('/', [
            'Accept-Language' => 'nl-NL,nl;q=0.9,en-US;q=0.8,en;q=0.7'
        ]);

        $this->assertEquals('nl', app()->getLocale());
    }

    public function test_api_responses_respect_locale()
    {
        $region = \App\Models\Region::factory()->create([
            'name' => ['en' => 'English Name', 'nl' => 'Nederlandse Naam']
        ]);

        // English
        $response = $this->getJson("/api/regions/{$region->slug}");
        $response->assertJsonPath('data.name', 'English Name');

        // Dutch via Accept-Language
        $response = $this->getJson("/api/regions/{$region->slug}", [
            'Accept-Language' => 'nl'
        ]);
        $response->assertJsonPath('data.name', 'Nederlandse Naam');
    }
}
