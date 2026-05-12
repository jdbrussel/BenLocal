<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
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
            'recommendation_id' => null,
            'overall_rating' => $this->faker->randomFloat(2, 1, 5),
            'rating_values' => [
                'food' => $this->faker->numberBetween(1, 5),
                'service' => $this->faker->numberBetween(1, 5),
                'ambience' => $this->faker->numberBetween(1, 5),
                'value' => $this->faker->numberBetween(1, 5),
            ],
            'review_text' => [$lang => $this->faker->paragraph()],
            'original_language' => $lang,
            'visited_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'confirms_recommendation' => $this->faker->boolean(80),
            'visibility_score' => $this->faker->randomFloat(2, 0, 1),
            'moderation_status' => \App\Enums\ModerationStatus::APPROVED,
            'verified_visit' => $this->faker->boolean(40),
        ];
    }
}
