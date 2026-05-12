<?php

namespace Database\Factories;

use App\Models\CampaignSubmission;
use App\Models\Campaign;
use App\Models\User;
use App\Models\Spot;
use Illuminate\Database\Eloquent\Factories\Factory;

class CampaignSubmissionFactory extends Factory
{
    protected $model = CampaignSubmission::class;

    public function definition(): array
    {
        return [
            'campaign_id' => Campaign::factory(),
            'user_id' => User::factory(),
            'matched_spot_id' => Spot::factory(),
            'submitted_name' => $this->faker->company(),
            'submitted_notes' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(['pending', 'shortlisted', 'rejected', 'selected']),
            'consent_to_terms' => true,
        ];
    }
}
