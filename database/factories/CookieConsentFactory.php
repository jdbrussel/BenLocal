<?php

namespace Database\Factories;

use App\Models\CookieConsent;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CookieConsentFactory extends Factory
{
    protected $model = CookieConsent::class;

    public function definition(): array
    {
        return [
            'user_id' => null,
            'session_id' => fake()->uuid(),
            'necessary' => true,
            'analytics' => fake()->boolean(),
            'personalization' => fake()->boolean(),
            'marketing' => fake()->boolean(),
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'consented_at' => now(),
        ];
    }
}
