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
        return [
            'user_id' => \App\Models\User::factory(),
            'spot_id' => \App\Models\Spot::factory(),
            'overall_rating' => $this->faker->randomFloat(2, 1, 5),
            'review_text' => ['en' => $this->faker->paragraph()],
            'moderation_status' => \App\Enums\ModerationStatus::APPROVED,
        ];
    }
}
