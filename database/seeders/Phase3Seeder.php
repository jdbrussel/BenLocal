<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Region;
use App\Models\Community;
use App\Models\CookieConsent;
use App\Models\UserRegionStatus;
use App\Models\NotificationPreference;
use App\Models\Follow;
use App\Enums\UserRegionStatus as UserRegionStatusEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class Phase3Seeder extends Seeder
{
    public function run(): void
    {
        $tenerife = Region::where('slug', 'tenerife')->first();
        $nlCommunity = Community::where('slug', 'netherlands')->first();
        $beCommunity = Community::where('slug', 'belgium')->first();
        $esCommunity = Community::where('slug', 'spain-canaries')->first();
        $deCommunity = Community::where('slug', 'germany')->first();
        $ukCommunity = Community::where('slug', 'united-kingdom')->first();
        $foodDrinks = \App\Models\Sector::where('slug', 'food-drinks')->first();
        $restaurants = \App\Models\Category::where('slug', 'restaurants')->first();

        // 1. Language & Theme Test Users
        $languageUsers = [
            ['name' => 'Willem de Boer', 'email' => 'willem@example.nl', 'lang' => 'nl', 'theme' => 'light', 'community' => $nlCommunity],
            ['name' => 'John Smith', 'email' => 'john@example.com', 'lang' => 'en', 'theme' => 'dark', 'community' => $ukCommunity],
            ['name' => 'Maria Garcia', 'email' => 'maria@example.es', 'lang' => 'es', 'theme' => 'system', 'community' => $esCommunity],
            ['name' => 'Hans Müller', 'email' => 'hans@example.de', 'lang' => 'de', 'theme' => 'light', 'community' => $deCommunity],
            ['name' => 'Jean Dupont', 'email' => 'jean@example.fr', 'lang' => 'fr', 'theme' => 'dark', 'community' => null],
        ];

        foreach ($languageUsers as $userData) {
            $user = User::factory()->create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'preferred_language' => $userData['lang'],
                'preferred_theme' => $userData['theme'],
                'community_id' => $userData['community']?->id,
                'email_verified_at' => now(),
            ]);

            $this->seedUserBasics($user);
        }

        // 2. Social Login Test Users
        $socialUsers = [
            ['name' => 'Google User', 'email' => 'google@example.com', 'provider' => 'google', 'pid' => 'g123'],
            ['name' => 'Facebook User', 'email' => 'facebook@example.com', 'provider' => 'facebook', 'pid' => 'f456'],
        ];

        foreach ($socialUsers as $sData) {
            $user = User::factory()->create([
                'name' => $sData['name'],
                'email' => $sData['email'],
                'provider' => $sData['provider'],
                'provider_id' => $sData['pid'],
                'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($sData['name']),
                'email_verified_at' => now(),
            ]);
            $this->seedUserBasics($user);
        }

        // 3. Status & Verification Scenarios
        $unverified = User::factory()->unverified()->create([
            'name' => 'Unverified User',
            'email' => 'unverified@example.com',
        ]);
        $this->seedUserBasics($unverified);

        // 4. Local Status Testing (Region Statuses)
        $locals = [
            ['name' => 'Tenerife Verified Local', 'status' => UserRegionStatusEnum::VERIFIED_LOCAL, 'score' => 95],
            ['name' => 'Tenerife Local', 'status' => UserRegionStatusEnum::LOCAL, 'score' => 80],
            ['name' => 'Regular Visitor', 'status' => UserRegionStatusEnum::REGULAR_VISITOR, 'score' => 60],
            ['name' => 'Tourist Tenerife', 'status' => UserRegionStatusEnum::TOURIST, 'score' => 10],
        ];

        $createdLocals = [];
        foreach ($locals as $l) {
            $user = User::factory()->create(['name' => $l['name']]);
            $this->seedUserBasics($user);

            UserRegionStatus::factory()->create([
                'user_id' => $user->id,
                'region_id' => $tenerife?->id,
                'status' => $l['status'],
                'confidence_score' => $l['score'],
                'verified_at' => $l['status'] === UserRegionStatusEnum::VERIFIED_LOCAL ? now() : null,
            ]);
            $createdLocals[] = $user;
        }

        // 5. Follow Network
        $dutchUser = User::where('email', 'willem@example.nl')->first();
        if ($dutchUser) {
            foreach ($createdLocals as $local) {
                Follow::factory()->create([
                    'follower_id' => $dutchUser->id,
                    'followed_id' => $local->id,
                ]);
            }
        }

        // 6. Multilingual Content Examples
        $this->seedMultilingualContent($foodDrinks, $restaurants, $tenerife);
    }

    private function seedMultilingualContent($sector, $category, $region): void
    {
        $spot = \App\Models\Spot::factory()->create([
            'name' => [
                'en' => 'Multilingual Spot',
                'nl' => 'Meertalige Plek',
                'es' => 'Lugar Multilingüe',
            ],
            'description' => [
                'en' => 'A spot with multiple translations.',
                'nl' => 'Een plek met meerdere vertalingen.',
                'es' => 'Un lugar con múltiples traducciones.',
            ],
            'sector_id' => $sector->id,
            'category_id' => $category->id,
            'region_id' => $region->id,
        ]);

        $user = User::first();
        if ($user) {
            \App\Models\Review::create([
                'user_id' => $user->id,
                'spot_id' => $spot->id,
                'rating' => 5,
                'comment' => [
                    'en' => 'Great place!',
                    'nl' => 'Geweldige plek!',
                    'es' => '¡Gran lugar!',
                ],
                'status' => 'active',
                'published_at' => now(),
            ]);
        }
    }

    private function seedUserBasics(User $user): void
    {
        // Cookie Consent
        CookieConsent::factory()->create([
            'user_id' => $user->id,
            'analytics' => fake()->boolean(),
            'marketing' => fake()->boolean(),
        ]);

        // Notification Preferences
        NotificationPreference::factory()->create([
            'user_id' => $user->id,
            'push_enabled' => fake()->boolean(),
            'marketing' => fake()->boolean(),
        ]);
    }
}
