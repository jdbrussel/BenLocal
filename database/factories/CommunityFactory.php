<?php

namespace Database\Factories;

use App\Models\Community;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CommunityFactory extends Factory
{
    protected $model = Community::class;

    public function definition(): array
    {
        $name = $this->faker->country();
        return [
            'name' => ['en' => $name],
            'slug' => Str::slug($name),
            'sort_order' => $this->faker->numberBetween(0, 100),
            'is_active' => true,
        ];
    }
}
