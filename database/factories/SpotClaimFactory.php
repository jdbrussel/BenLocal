<?php

namespace Database\Factories;

use App\Models\SpotClaim;
use App\Models\User;
use App\Models\Spot;
use Illuminate\Database\Eloquent\Factories\Factory;

class SpotClaimFactory extends Factory
{
    protected $model = SpotClaim::class;

    public function definition(): array
    {
        return [
            'spot_id' => Spot::factory(),
            'user_id' => User::factory(),
            'business_name' => $this->faker->company(),
            'contact_name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'website' => $this->faker->url(),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'proof_notes' => $this->faker->sentence(),
        ];
    }
}
