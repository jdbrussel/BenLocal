<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Place>
 */
class PlaceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->city();
        return [
            'area_id' => \App\Models\Area::factory(),
            'name' => ['en' => $name, 'es' => $name],
            'slug' => \Illuminate\Support\Str::slug($name),
            'description' => ['en' => $this->faker->paragraph()],
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
            'is_active' => true,
        ];
    }
}
