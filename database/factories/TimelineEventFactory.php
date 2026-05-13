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
            'type' => $this->faker->randomElement([
                'recommendation_created',
                'review_created',
                'review_reaction_created',
                'user_followed',
                'user_tagged_in_review',
                'hidden_gem_detected',
                'spot_status_changed',
                'campaign_submission_created',
                'campaign_recommendation_created',
                'spot_saved',
                'business_claim_created',
                'business_claim_approved'
            ]),
            'eventable_type' => null,
            'eventable_id' => null,
            'payload' => [],
            'region_id' => Region::factory(),
            'created_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
        ];
    }
}
