<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CommunitySeeder::class,
            LocationSeeder::class,
            SectorCategorySeeder::class,
            CategorySpecsSeeder::class,
            UserSeeder::class,
            SpotSeeder::class,
            RecommendationSeeder::class,
            ReviewSeeder::class,
            CampaignSeeder::class,
            BusinessClaimSeeder::class,
            CmsPageSeeder::class,
            ModerationSeeder::class,
            NotificationPreferenceSeeder::class,
            Phase3Seeder::class,
        ]);

        User::updateOrCreate(
            ['email' => 'jasper@studiobxl.com'],
            [
                'name' => 'Jasper Brussel',
                'password' => \Illuminate\Support\Facades\Hash::make('jasperStudioBxl!'),
            ]
        );
    }
}
