<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Campaign>
 */
class CampaignFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->words(3, true);
        return [
            'name' => [
                'en' => $name,
                'nl' => $name . ' (NL)',
            ],
            'slug' => \Illuminate\Support\Str::slug($name),
            'description' => [
                'en' => $this->faker->sentence(),
                'nl' => $this->faker->sentence(),
            ],
            'is_active' => true,
            'starts_at' => now(),
            'ends_at' => now()->addMonth(),
        ];
    }
}
