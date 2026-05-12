<?php

namespace Database\Factories;

use App\Models\SavedSpot;
use App\Models\Spot;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SavedSpotFactory extends Factory
{
    protected $model = SavedSpot::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'spot_id' => Spot::factory(),
            'created_at' => $this->faker->dateTimeBetween('-3 months', 'now'),
        ];
    }
}
