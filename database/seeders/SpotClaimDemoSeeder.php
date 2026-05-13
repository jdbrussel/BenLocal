<?php

namespace Database\Seeders;

use App\Models\Spot;
use App\Models\User;
use App\Models\SpotClaim;
use App\Enums\ClaimStatus;
use Illuminate\Database\Seeder;

class SpotClaimDemoSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', \App\Enums\UserRole::ADMIN)->first();

        $scenarios = [
            [
                'spot_slug' => 'bodega-san-miguel',
                'user_email' => 'owner.bodega@example.com',
                'status' => ClaimStatus::APPROVED,
                'business_name' => 'Bodega San Miguel',
                'contact_name' => 'Mario Rossi',
                'email' => 'owner.bodega@example.com',
                'approved_at' => now(),
                'approved_by' => $admin?->id,
            ],
            [
                'spot_slug' => 'cafe-vlaanderen',
                'user_email' => 'owner.cafevlaanderen@example.com',
                'status' => ClaimStatus::APPROVED,
                'business_name' => 'Café Vlaanderen',
                'contact_name' => 'Jan Janssens',
                'email' => 'owner.cafevlaanderen@example.com',
                'approved_at' => now()->subDays(2),
                'approved_by' => $admin?->id,
            ],
            [
                'spot_slug' => 'restaurante-mar-azul',
                'user_email' => 'owner.marazul@example.com',
                'status' => ClaimStatus::PENDING,
                'business_name' => 'Restaurante Mar Azul',
                'contact_name' => 'Maria Garcia',
                'email' => 'owner.marazul@example.com',
                'proof_notes' => 'Attached registration documents.',
            ],
            [
                'spot_slug' => 'puerto-beach-bar',
                'user_email' => 'manager.beachbar@example.com',
                'status' => ClaimStatus::REJECTED,
                'business_name' => 'Puerto Beach Bar',
                'contact_name' => 'John Smith',
                'email' => 'manager.beachbar@example.com',
                'rejection_reason' => 'Proof of ownership is insufficient.',
            ],
            [
                'spot_slug' => 'guachinche-casa-pepe',
                'user_email' => 'owner.casapepe@example.com',
                'status' => ClaimStatus::PENDING, // Used for "More info needed" logic in Filament if needed, but here we use PENDING
                'business_name' => 'Guachinche Casa Pepe',
                'contact_name' => 'Jose Pepe',
                'email' => 'owner.casapepe@example.com',
                'proof_notes' => 'I only have a utility bill.',
            ],
        ];

        foreach ($scenarios as $data) {
            $spot = Spot::where('slug', $data['spot_slug'])->first();
            $user = User::where('email', $data['user_email'])->first();

            if ($spot && $user) {
                SpotClaim::updateOrCreate(
                    ['spot_id' => $spot->id, 'user_id' => $user->id],
                    collect($data)->except(['spot_slug', 'user_email'])->toArray()
                );

                if ($data['status'] === ClaimStatus::APPROVED) {
                    $spot->update(['is_claimed' => true, 'verified_business' => true]);
                }
            }
        }

        // Edge case: Duplicate claim request
        $competitorEmail = 'competitor@example.com';
        $anotherUser = User::where('email', $competitorEmail)->first();
        if (!$anotherUser) {
            $anotherUser = User::factory()->create(['email' => $competitorEmail, 'role' => \App\Enums\UserRole::OWNER]);
        }
        $spot = Spot::where('slug', 'bodega-san-miguel')->first();
        if ($spot) {
            SpotClaim::updateOrCreate(
                ['spot_id' => $spot->id, 'user_id' => $anotherUser->id],
                [
                    'business_name' => 'Bodega San Miguel Fake',
                    'contact_name' => 'Bad Actor',
                    'email' => $competitorEmail,
                    'status' => ClaimStatus::PENDING,
                ]
            );
        }
    }
}
