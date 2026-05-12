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
        $lang = $this->faker->randomElement(['en', 'nl', 'es', 'de', 'fr']);
        return [
            'user_id' => \App\Models\User::factory(),
            'spot_id' => \App\Models\Spot::factory(),
            'region_id' => \App\Models\Region::factory(),
            'community_id' => \App\Models\Community::factory(),
            'title' => [$lang => $this->faker->sentence()],
            'motivation' => [$lang => $this->faker->paragraph()],
            'original_language' => $lang,
            'confidence_score' => $this->faker->randomFloat(2, 0, 1),
            'hidden_gem_candidate' => $this->faker->boolean(20),
            'moderation_status' => \App\Enums\ModerationStatus::APPROVED,
        ];
    }
}
