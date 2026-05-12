<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Recommendation>
 */
class RecommendationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'spot_id' => \App\Models\Spot::factory(),
            'region_id' => \App\Models\Region::factory(),
            'title' => ['en' => $this->faker->sentence()],
            'motivation' => ['en' => $this->faker->paragraph()],
            'moderation_status' => \App\Enums\ModerationStatus::APPROVED,
        ];
    }
}
