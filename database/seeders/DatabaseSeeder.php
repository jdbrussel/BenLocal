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
            CampaignSeeder::class,
            Phase4UXSeeder::class,
            Phase5DiscoverySeeder::class,
            RecommendationSeeder::class,
            ReviewSeeder::class,
            Phase8FeedSeeder::class,
            Phase9CampaignSeeder::class,
            BusinessClaimSeeder::class,
            BusinessOwnerSeeder::class,
            SpotClaimDemoSeeder::class,
            ClaimTokenDemoSeeder::class,
            SpotOwnerRoleSeeder::class,
            OwnerReviewResponseSeeder::class,
            CmsPageSeeder::class,
            ModerationSeeder::class,
            NotificationPreferenceSeeder::class,
            Phase3Seeder::class,
            ReviewPhotoSeeder::class,
            ReviewReactionSeeder::class,
            ReviewTagSeeder::class,
            TimelineEventSeeder::class,
            FollowActivitySeeder::class,
            RecommendationActivitySeeder::class,
            ReviewActivitySeeder::class,
            HiddenGemActivitySeeder::class,
            CampaignActivitySeeder::class,
            FeedDemoSeeder::class,
        ]);

        User::updateOrCreate(
            [
                'email' => 'jasper@studiobxl.com',
            ],
            [
                'name' => 'Jasper Brussel',
                'password' => \Illuminate\Support\Facades\Hash::make('jasperStudioBxl!'),
                'role' => \App\Enums\UserRole::ADMIN,
            ]
        );
    }
}
