<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'preferred_language' => fake()->randomElement(['en', 'nl', 'es', 'de', 'fr']),
            'preferred_theme' => fake()->randomElement(['light', 'dark', 'system']),
            'country' => fake()->country(),
            'city' => fake()->city(),
            'residence_region_id' => null,
            'residence_area_id' => null,
            'residence_place_id' => null,
            'community_id' => null,
            'provider' => null,
            'provider_id' => null,
            'avatar' => null,
            'trust_penalty_score' => 0,
            'is_shadowbanned' => false,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
