<?php

namespace Database\Seeders;

use App\Models\Spot;
use App\Models\SpotVisit;
use App\Models\User;
use App\Models\TimelineEvent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class SpotVisitDemoSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $spots = Spot::where('lifecycle_status', 'active')->get();

        if ($users->isEmpty() || $spots->isEmpty()) {
            return;
        }

        $sources = [
            'gps' => ['min' => 0.85, 'max' => 1.00],
            'qr' => ['min' => 0.95, 'max' => 1.00],
            'reservation' => ['min' => 0.75, 'max' => 0.90],
            'owner_confirmation' => ['min' => 0.80, 'max' => 0.95],
            'manual' => ['min' => 0.20, 'max' => 0.50],
        ];

        // We'll generate a base set of visits here, others will be handled by specialized seeders
        // to meet the specific count requirements.

        // Target: ~400 total in this seeder to leave room for others.
        for ($i = 0; $i < 400; $i++) {
            $user = $users->random();
            $spot = $spots->random();
            $sourceKey = array_rand($sources);
            $score = $sources[$sourceKey];

            $this->createVisit($user, $spot, $sourceKey, rand($score['min'] * 100, $score['max'] * 100) / 100);
        }
    }

    private function createVisit($user, $spot, $source, $score, $isSuspicious = false, $lat = null, $lng = null)
    {
        $visit = SpotVisit::create([
            'user_id' => $user->id,
            'spot_id' => $spot->id,
            'checked_in_at' => Carbon::now()->subDays(rand(0, 60))->subHours(rand(0, 23)),
            'visit_source' => $source,
            'latitude' => $lat ?? ($spot->latitude ? $spot->latitude + (rand(-100, 100) / 100000) : null),
            'longitude' => $lng ?? ($spot->longitude ? $spot->longitude + (rand(-100, 100) / 100000) : null),
            'verification_score' => $score,
            'is_suspicious' => $isSuspicious,
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
                'visit_source' => $source,
                'verification_score' => $score,
            ],
        ]);

        if ($score >= 0.7) {
             TimelineEvent::create([
                'user_id' => $user->id,
                'type' => 'visit_verified',
                'eventable_type' => SpotVisit::class,
                'eventable_id' => $visit->id,
                'region_id' => $spot->region_id,
                'payload' => [
                    'spot_id' => $spot->id,
                    'verification_score' => $score,
                ],
            ]);
        }

        return $visit;
    }
}
