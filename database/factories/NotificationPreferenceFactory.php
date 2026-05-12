<?php

namespace Database\Factories;

use App\Models\NotificationPreference;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationPreferenceFactory extends Factory
{
    protected $model = NotificationPreference::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'new_followers' => true,
            'review_replies' => true,
            'recommendation_validation' => true,
            'tagged_in_review' => true,
            'hidden_gem_updates' => true,
            'local_status_updates' => true,
            'spot_updates' => true,
            'marketing' => fake()->boolean(),
            'email_enabled' => true,
            'push_enabled' => fake()->boolean(),
        ];
    }
}
