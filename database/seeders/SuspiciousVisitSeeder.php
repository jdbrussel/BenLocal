<?php

namespace Database\Seeders;

use App\Models\Spot;
use App\Models\SpotVisit;
use App\Models\User;
use App\Models\TimelineEvent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class SuspiciousVisitSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $spots = Spot::all();

        // Target: 30+ suspicious visits
        for ($i = 0; $i < 35; $i++) {
            $user = $users->random();
            $spot = $spots->random();

            // Suspicious far-away: user claims Puerto Colón visit but coordinates are in La Laguna (approx 80km away)
            // Puerto Colón approx: 28.08, -16.73
            // La Laguna approx: 28.48, -16.31

            $lat = 28.48 + (rand(-100, 100) / 10000);
            $lng = -16.31 + (rand(-100, 100) / 10000);

            $visit = SpotVisit::create([
                'user_id' => $user->id,
                'spot_id' => $spot->id,
                'checked_in_at' => Carbon::now()->subDays(rand(0, 10)),
                'visit_source' => 'gps',
                'latitude' => $lat,
                'longitude' => $lng,
                'verification_score' => rand(0, 20) / 100,
                'is_suspicious' => true,
                'metadata' => [
                    'distance_km' => 80.5,
                    'reason' => 'Distance exceeds maximum allowed threshold',
                ],
            ]);

            TimelineEvent::create([
                'user_id' => $user->id,
                'type' => 'visit_logged',
                'eventable_type' => SpotVisit::class,
                'eventable_id' => $visit->id,
                'region_id' => $spot->region_id,
                'payload' => [
                    'spot_id' => $spot->id,
                    'spot_name' => $spot->getTranslation('name', 'en'),
                    'visit_source' => 'gps',
                    'verification_score' => $visit->verification_score,
                    'is_suspicious' => true,
                ],
            ]);
        }
    }
}
