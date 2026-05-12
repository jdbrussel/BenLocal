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
        return [
            'name' => ['en' => $name],
            'slug' => \Illuminate\Support\Str::slug($name),
            'description' => ['en' => $this->faker->paragraph()],
            'sector_id' => \App\Models\Sector::factory(),
            'category_id' => \App\Models\Category::factory(),
            'region_id' => \App\Models\Region::factory(),
            'lifecycle_status' => \App\Enums\SpotLifecycleStatus::ACTIVE,
            'is_claimed' => false,
            'verified_business' => false,
            'created_by' => \App\Models\User::factory(),
        ];
    }
}
