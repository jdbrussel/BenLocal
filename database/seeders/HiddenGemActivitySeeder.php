<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HiddenGemActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $spots = \App\Models\Spot::all();
        $tenerife = \App\Models\Region::where('slug', 'tenerife')->first();

        $hiddenGemSpots = $spots->filter(fn($s) => str_contains(strtolower($s->name), 'guachinche') || str_contains(strtolower($s->name), 'bodega') || str_contains(strtolower($s->name), 'casa'));

        foreach ($hiddenGemSpots->take(25) as $spot) {
            \App\Models\TimelineEvent::create([
                'user_id' => null,
                'type' => 'hidden_gem_detected',
                'eventable_type' => \App\Models\Spot::class,
                'eventable_id' => $spot->id,
                'region_id' => $spot->region_id ?? $tenerife->id,
                'payload' => [
                    'spot_id' => $spot->id,
                    'spot_name' => $spot->name,
                    'reason' => 'Few recommendations, strong local validation',
                ],
                'created_at' => now()->subDays(rand(1, 30)),
            ]);

            \App\Models\TimelineEvent::create([
                'user_id' => null,
                'type' => 'spot_status_changed',
                'eventable_type' => \App\Models\Spot::class,
                'eventable_id' => $spot->id,
                'region_id' => $spot->region_id ?? $tenerife->id,
                'payload' => [
                    'spot_id' => $spot->id,
                    'spot_name' => $spot->name,
                    'new_status' => 'Hidden Gem',
                ],
                'created_at' => now()->subDays(rand(1, 15)),
            ]);
        }

        // Spot saved events
        $users = \App\Models\User::all();
        for ($i = 0; $i < 50; $i++) {
            $user = $users->random();
            $spot = $spots->random();

            \App\Models\TimelineEvent::create([
                'user_id' => $user->id,
                'type' => 'spot_saved',
                'eventable_type' => \App\Models\Spot::class,
                'eventable_id' => $spot->id,
                'region_id' => $spot->region_id ?? $tenerife->id,
                'payload' => [
                    'spot_id' => $spot->id,
                    'spot_name' => $spot->name,
                    'user_name' => $user->name,
                ],
                'created_at' => now()->subDays(rand(1, 30)),
            ]);
        }
    }
}
