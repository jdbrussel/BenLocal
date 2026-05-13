<?php

namespace Database\Seeders;

use App\Enums\ClaimStatus;
use App\Models\Campaign;
use App\Models\ClaimToken;
use App\Models\Spot;
use App\Models\SpotClaim;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BusinessClaimSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all()->keyBy('email');
        $spots = Spot::all()->keyBy('slug');
        $campaign = Campaign::where('slug', 'tafelen-in-tenerife')->first();

        // Approved Claim
        if (isset($spots['cafe-vlaanderen']) && isset($users['sofie@benlocal.test'])) {
            SpotClaim::updateOrCreate(
                ['spot_id' => $spots['cafe-vlaanderen']->id, 'user_id' => $users['sofie@benlocal.test']->id],
                [
                    'business_name' => 'Café Vlaanderen',
                    'contact_name' => 'Sofie Peeters',
                    'email' => 'sofie@benlocal.test',
                    'status' => ClaimStatus::APPROVED,
                    'approved_at' => now(),
                    'approved_by' => $users['carlos@benlocal.test']->id ?? null,
                ]
            );
        }

        // Pending Claim
        if (isset($spots['restaurante-mar-azul']) && isset($users['jan@benlocal.test'])) {
            SpotClaim::updateOrCreate(
                ['spot_id' => $spots['restaurante-mar-azul']->id, 'user_id' => $users['jan@benlocal.test']->id],
                [
                    'business_name' => 'Restaurante Mar Azul',
                    'contact_name' => 'Jan de Hollander',
                    'email' => 'jan@benlocal.test',
                    'status' => ClaimStatus::PENDING,
                ]
            );
        }

        // Rejected Claim
        if (isset($spots['puerto-beach-bar']) && isset($users['emma@benlocal.test'])) {
            SpotClaim::updateOrCreate(
                ['spot_id' => $spots['puerto-beach-bar']->id, 'user_id' => $users['emma@benlocal.test']->id],
                [
                    'business_name' => 'Puerto Beach Bar',
                    'contact_name' => 'Emma Smith',
                    'email' => 'emma@benlocal.test',
                    'status' => ClaimStatus::REJECTED,
                    'rejection_reason' => 'Geen bewijs van eigendom verstrekt.',
                ]
            );
        }

        // Claim Tokens
        foreach ($spots as $spot) {
            ClaimToken::updateOrCreate(
                ['spot_id' => $spot->id],
                [
                    'campaign_id' => $campaign?->id,
                    'token' => Str::random(32),
                    'email' => $spot->email ?? 'owner@' . $spot->slug . '.com',
                    'expires_at' => now()->addDays(30),
                ]
            );
        }
    }
}
