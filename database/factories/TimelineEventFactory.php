<?php

namespace Database\Factories;

use App\Models\TimelineEvent;
use App\Models\User;
use App\Models\Region;
use Illuminate\Database\Eloquent\Factories\Factory;

class TimelineEventFactory extends Factory
{
    protected $model = TimelineEvent::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'type' => $this->faker->randomElement(['recommendation', 'review', 'follow', 'hidden_gem_discovery']),
            'eventable_type' => 'App\Models\Recommendation',
            'eventable_id' => 1,
            'payload' => [],
            'region_id' => Region::factory(),
            'created_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
