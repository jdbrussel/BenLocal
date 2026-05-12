<?php

namespace Database\Factories;

use App\Models\SpotVisit;
use App\Models\User;
use App\Models\Spot;
use Illuminate\Database\Eloquent\Factories\Factory;

class SpotVisitFactory extends Factory
{
    protected $model = SpotVisit::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'spot_id' => Spot::factory(),
            'checked_in_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'visit_source' => $this->faker->randomElement(['gps', 'manual', 'qr']),
            'latitude' => $this->faker->latitude(28.0, 28.6),
            'longitude' => $this->faker->longitude(-16.8, -16.2),
            'verification_score' => $this->faker->randomFloat(2, 0, 1),
        ];
    }
}
