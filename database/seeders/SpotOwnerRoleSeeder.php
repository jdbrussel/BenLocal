<?php

namespace Database\Seeders;

use App\Models\Spot;
use App\Models\User;
use App\Models\SpotOwnerRole;
use Illuminate\Database\Seeder;

class SpotOwnerRoleSeeder extends Seeder
{
    public function run(): void
    {
        $assignments = [
            [
                'spot_slug' => 'cafe-vlaanderen',
                'user_email' => 'owner.cafevlaanderen@example.com',
                'role' => 'owner',
            ],
            [
                'spot_slug' => 'cafe-vlaanderen',
                'user_email' => 'manager.beachbar@example.com', // Using this user as a manager for test
                'role' => 'manager',
            ],
            [
                'spot_slug' => 'bodega-san-miguel',
                'user_email' => 'owner.bodega@example.com',
                'role' => 'owner',
            ],
            [
                'spot_slug' => 'puerto-beach-bar',
                'user_email' => 'manager.beachbar@example.com',
                'role' => 'editor',
            ],
        ];

        foreach ($assignments as $data) {
            $spot = Spot::where('slug', $data['spot_slug'])->first();
            $user = User::where('email', $data['user_email'])->first();

            if ($spot && $user) {
                SpotOwnerRole::updateOrCreate(
                    ['spot_id' => $spot->id, 'user_id' => $user->id],
                    ['role' => $data['role']]
                );
            }
        }
    }
}
