<?php

namespace Database\Factories;

use App\Models\UserRegionStatus;
use App\Models\User;
use App\Models\Region;
use App\Enums\UserRegionStatus as UserRegionStatusEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserRegionStatusFactory extends Factory
{
    protected $model = UserRegionStatus::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'region_id' => null, // Should be provided
            'status' => fake()->randomElement(UserRegionStatusEnum::cases()),
            'claimed_by_user' => fake()->boolean(),
            'residence_based' => fake()->boolean(),
            'ip_supported' => fake()->boolean(),
            'manually_verified' => fake()->boolean(),
            'confidence_score' => fake()->randomFloat(2, 0, 100),
            'verified_at' => fake()->optional()->dateTime(),
        ];
    }
}
