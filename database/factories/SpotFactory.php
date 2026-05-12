<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Spot>
 */
class SpotFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->company();
        $lang = $this->faker->randomElement(['en', 'nl', 'es']);

        return [
            'name' => [$lang => $name],
            'slug' => \Illuminate\Support\Str::slug($name),
            'description' => [$lang => $this->faker->paragraph()],
            'original_language' => $lang,
            'sector_id' => \App\Models\Sector::factory(),
            'category_id' => \App\Models\Category::factory(),
            'region_id' => \App\Models\Region::factory(),
            'address' => [
                'street' => $this->faker->streetAddress(),
                'city' => $this->faker->city(),
                'postal_code' => $this->faker->postcode(),
            ],
            'latitude' => $this->faker->latitude(28.0, 28.6), // Tenerife range
            'longitude' => $this->faker->longitude(-16.8, -16.2),
            'price_level' => $this->faker->numberBetween(1, 4),
            'spec_values' => [
                'venue_type' => $this->faker->randomElements(['restaurant', 'bar', 'cafe', 'guachinche', 'bodega'], rand(1, 2)),
                'cuisine' => $this->faker->randomElements(['canarian', 'spanish', 'italian', 'belgian', 'dutch', 'international'], rand(1, 2)),
                'price_class' => $this->faker->randomElement(['€', '€€', '€€€', '€€€€']),
                'terrace' => $this->faker->boolean(70),
                'sea_view' => $this->faker->boolean(30),
            ],
            'lifecycle_status' => \App\Enums\SpotLifecycleStatus::ACTIVE,
            'is_claimed' => false,
            'verified_business' => false,
            'created_by' => \App\Models\User::factory(),
        ];
    }
}
