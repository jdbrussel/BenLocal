<?php

namespace Database\Seeders;

use App\Models\Spot;
use App\Models\SpotVisit;
use App\Models\User;
use App\Models\TimelineEvent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ManualVisitDemoSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $spots = Spot::all();
        $emma = User::where('email', 'emma@benlocal.test')->first();

        // Target: 80+ manual visits

        // Specific scenario for Emma
        if ($emma) {
            $beachBar = Spot::where('slug', 'puerto-beach-bar')->first();
            if ($beachBar) {
                $this->createManualVisit($emma, $beachBar, 0.4);
            }
        }

        // Random manual visits
        for ($i = 0; $i < 80; $i++) {
            $user = $users->random();
            $spot = $spots->random();
            $this->createManualVisit($user, $spot, rand(20, 50) / 100);
        }
    }

    private function createManualVisit($user, $spot, $score)
    {
        $visit = SpotVisit::create([
            'user_id' => $user->id,
            'spot_id' => $spot->id,
            'checked_in_at' => Carbon::now()->subDays(rand(0, 60)),
            'visit_source' => 'manual',
            'latitude' => null,
            'longitude' => null,
            'verification_score' => $score,
            'is_suspicious' => false,
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
                'visit_source' => 'manual',
                'verification_score' => $score,
            ],
        ]);
    }
}
