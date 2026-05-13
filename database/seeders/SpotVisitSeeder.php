<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\Spot;
use App\Models\SpotVisit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SpotVisitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $spots = Spot::whereNotNull('latitude')->whereNotNull('longitude')->get();

        if ($users->isEmpty() || $spots->isEmpty()) {
            return;
        }

        foreach ($spots as $spot) {
            // Set a QR token for some spots
            if (rand(0, 1)) {
                $spot->update(['qr_token' => Str::random(10)]);
            }

            // Create some GPS verified visits
            for ($i = 0; $i < 2; $i++) {
                $user = $users->random();
                SpotVisit::create([
                    'user_id' => $user->id,
                    'spot_id' => $spot->id,
                    'checked_in_at' => now()->subDays(rand(1, 30)),
                    'visit_source' => 'gps',
                    'latitude' => (float)$spot->latitude + (rand(-10, 10) / 100000),
                    'longitude' => (float)$spot->longitude + (rand(-10, 10) / 100000),
                    'verification_score' => 1.0,
                    'is_suspicious' => false,
                ]);
            }

            // Create a QR visit if token exists
            if ($spot->qr_token) {
                $user = $users->random();
                SpotVisit::create([
                    'user_id' => $user->id,
                    'spot_id' => $spot->id,
                    'checked_in_at' => now()->subDays(rand(1, 30)),
                    'visit_source' => 'qr',
                    'verification_score' => 1.0,
                    'is_suspicious' => false,
                ]);
            }

            // Create a manual visit
            $user = $users->random();
            SpotVisit::create([
                'user_id' => $user->id,
                'spot_id' => $spot->id,
                'checked_in_at' => now()->subDays(rand(1, 30)),
                'visit_source' => 'manual',
                'verification_score' => 0.2,
                'is_suspicious' => false,
            ]);

            // Create a suspicious visit (far away)
            $user = $users->random();
            SpotVisit::create([
                'user_id' => $user->id,
                'spot_id' => $spot->id,
                'checked_in_at' => now()->subDays(rand(1, 30)),
                'visit_source' => 'gps',
                'latitude' => (float)$spot->latitude + 1.0, // 1 degree away is very far
                'longitude' => (float)$spot->longitude + 1.0,
                'verification_score' => 0.1,
                'is_suspicious' => true,
            ]);
        }

        // Link some reviews to visits
        $visits = SpotVisit::where('verification_score', '>=', 0.8)->where('is_suspicious', false)->get();
        foreach ($visits->random(min(10, $visits->count())) as $visit) {
            Review::create([
                'user_id' => $visit->user_id,
                'spot_id' => $visit->spot_id,
                'spot_visit_id' => $visit->id,
                'overall_rating' => rand(3, 5),
                'review_text' => ['en' => 'Verified visit review!'],
                'visited_at' => $visit->checked_in_at,
                'verified_visit' => true,
                'visibility_score' => 1.0,
            ]);
        }
    }
}
