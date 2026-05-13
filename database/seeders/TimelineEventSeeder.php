<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TimelineEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = \App\Models\User::all();
        $regions = \App\Models\Region::all();
        $tenerife = $regions->where('slug', 'tenerife')->first();

        // Generate 500-1000 events
        $count = rand(500, 1000);

        \App\Models\TimelineEvent::factory($count)->create([
            'user_id' => fn() => $users->random()->id,
            'region_id' => fn() => $regions->random()->id,
        ]);

        // Ensure some are definitely Tenerife
        \App\Models\TimelineEvent::factory(200)->create([
            'region_id' => $tenerife->id,
            'user_id' => fn() => $users->random()->id,
        ]);
    }
}
