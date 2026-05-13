<?php

namespace Database\Seeders;

use App\Models\Spot;
use App\Models\SpotVisit;
use App\Models\User;
use App\Models\TimelineEvent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class QrVisitDemoSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $spots = Spot::where('verified_business', true)->get();
        $sofie = User::where('email', 'sofie@benlocal.test')->first();

        // Target: 50+ QR visits

        // Seed some specific scenarios first
        if ($sofie) {
            $vlaanderen = Spot::where('slug', 'cafe-vlaanderen')->first();
            if ($vlaanderen) {
                // Valid QR token
                $vlaanderen->update(['qr_token' => 'VALID_QR_123']);
                $this->createQrVisit($sofie, $vlaanderen, 0.99, 'VALID_QR_123');
            }
        }

        // Random QR visits
        for ($i = 0; $i < 50; $i++) {
            $user = $users->random();
            $spot = $spots->random();
            $token = Str::random(16);

            // Occasionally use the same token for the same spot
            if ($i % 10 === 0) {
                 $spot->update(['qr_token' => $token]);
            }

            $this->createQrVisit($user, $spot, rand(95, 100) / 100, $token);
        }

        // Edge cases
        $expiredToken = 'EXPIRED_TOKEN';
        $revokedToken = 'REVOKED_TOKEN';

        // Visit with "expired" token (low score/suspicious)
        $this->createQrVisit($users->random(), $spots->random(), 0.1, $expiredToken, true, 'Token expired');

        // Visit with "revoked" token
        $this->createQrVisit($users->random(), $spots->random(), 0.05, $revokedToken, true, 'Token revoked');
    }

    private function createQrVisit($user, $spot, $score, $token, $isSuspicious = false, $reason = null)
    {
        $visit = SpotVisit::create([
            'user_id' => $user->id,
            'spot_id' => $spot->id,
            'checked_in_at' => Carbon::now()->subDays(rand(0, 30)),
            'visit_source' => 'qr',
            'latitude' => $spot->latitude,
            'longitude' => $spot->longitude,
            'verification_score' => $score,
            'is_suspicious' => $isSuspicious,
            'metadata' => [
                'qr_token' => $token,
                'suspicious_reason' => $reason,
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
                'visit_source' => 'qr',
                'verification_score' => $score,
                'is_suspicious' => $isSuspicious,
            ],
        ]);

        if (!$isSuspicious && $score >= 0.7) {
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
    }
}
