<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\LocaleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class LocaleTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_resolves_default_locale()
    {
        $service = new LocaleService();
        $this->assertEquals('en', $service->resolveLocale());
    }

    public function test_it_resolves_user_preferred_locale()
    {
        $user = User::factory()->create(['preferred_language' => 'nl']);
        $this->actingAs($user);

        $service = new LocaleService();
        $this->assertEquals('nl', $service->resolveLocale());
    }

    public function test_it_resolves_browser_locale_for_guests()
    {
        request()->headers->set('Accept-Language', 'es-ES,es;q=0.9');

        $service = new LocaleService();
        $this->assertEquals('es', $service->detectBrowserLocale());
    }

    public function test_it_resolves_cookie_locale_for_guests()
    {
        request()->cookies->set('benlocal_locale', 'fr');

        $service = new LocaleService();
        $this->assertEquals('fr', $service->resolveLocale());
    }
}
