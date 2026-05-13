<?php

namespace Database\Seeders;

use App\Models\Spot;
use App\Models\Campaign;
use App\Models\ClaimToken;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ClaimTokenDemoSeeder extends Seeder
{
    public function run(): void
    {
        $campaign = Campaign::where('slug', 'tafelen-in-tenerife')->first();
        $spots = Spot::where('is_claimed', false)->take(5)->get();

        if ($spots->count() > 0) {
            // Valid token
            ClaimToken::updateOrCreate(
                ['email' => 'valid@example.com'],
                [
                    'spot_id' => $spots[0]->id,
                    'token' => Str::random(32),
                    'expires_at' => now()->addDays(30),
                ]
            );

            // Expired token
            if (isset($spots[1])) {
                ClaimToken::updateOrCreate(
                    ['email' => 'expired@example.com'],
                    [
                        'spot_id' => $spots[1]->id,
                        'token' => Str::random(32),
                        'expires_at' => now()->subDays(1),
                    ]
                );
            }

            // Used token
            if (isset($spots[2])) {
                ClaimToken::updateOrCreate(
                    ['email' => 'used@example.com'],
                    [
                        'spot_id' => $spots[2]->id,
                        'token' => Str::random(32),
                        'expires_at' => now()->addDays(30),
                        'used_at' => now()->subDays(1),
                    ]
                );
            }

            // Campaign-related token
            if (isset($spots[3]) && $campaign) {
                ClaimToken::updateOrCreate(
                    ['token' => 'CAMPAIGN-TOKEN-123'],
                    [
                        'spot_id' => $spots[3]->id,
                        'campaign_id' => $campaign->id,
                        'email' => 'campaign@example.com',
                        'expires_at' => now()->addDays(60),
                    ]
                );
            }

            // Edge case: mismatched email domain
            if (isset($spots[4])) {
                 ClaimToken::updateOrCreate(
                    ['email' => 'owner@another-domain.com'],
                    [
                        'spot_id' => $spots[4]->id,
                        'token' => Str::random(32),
                        'expires_at' => now()->addDays(30),
                    ]
                );
            }
        }
    }
}
