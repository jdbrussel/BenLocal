<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Region;
use App\Models\Community;
use App\Models\Sector;
use App\Models\Category;
use App\Models\Area;
use App\Models\Place;
use App\Models\Spot;
use App\Models\SpotBadge;
use App\Models\Recommendation;
use App\Models\Review;
use App\Models\ReviewReaction;
use App\Models\TimelineEvent;
use App\Models\Follow;
use App\Models\SpotVisit;
use App\Models\SpotClaim;
use App\Models\Campaign;
use App\Models\CampaignSubmission;
use App\Models\CookieConsent;
use App\Models\NotificationPreference;
use App\Models\UserRegionStatus;
use App\Models\SpotCommunityProfile;
use App\Enums\UserRegionStatus as UserRegionStatusEnum;
use App\Enums\ModerationStatus;
use App\Enums\ReviewReactionType;
use App\Enums\SpotLifecycleStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class Phase4UXSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Core Data Lookups
        $tenerife = Region::where('slug', 'tenerife')->first();
        $communities = Community::all();
        $nlComm = $communities->where('slug', 'netherlands')->first();
        $beComm = $communities->where('slug', 'belgium')->first();
        $esComm = $communities->where('slug', 'spain-canaries')->first();
        $deComm = $communities->where('slug', 'germany')->first();
        $ukComm = $communities->where('slug', 'united-kingdom')->first();

        $foodDrinks = Sector::where('slug', 'food-drinks')->first();
        $restaurants = Category::where('slug', 'restaurants')->first();
        $bars = Category::where('slug', 'bars')->first();

        // 2. Ensure Areas & Places for realistic density
        $areas = $this->seedTenerifeAreas($tenerife);

        // 3. Create Hidden Gem Badge
        $hiddenGemBadge = SpotBadge::firstOrCreate(['key' => 'hidden-gem'], [
            'name' => ['en' => 'Hidden Gem', 'nl' => 'Verborgen Parel', 'es' => 'Joya Oculta'],
            'description' => ['en' => 'Locals love this place, but tourists haven\'t found it yet.'],
            'icon' => 'gem',
            'color' => 'purple',
        ]);

        // 4. Seed Diverse Users (40 Users)
        $users = $this->seedDiverseUsers($tenerife, $communities);

        // 5. Seed Spots (60 Spots)
        $spots = $this->seedRealisticSpots($tenerife, $areas, $foodDrinks, $restaurants, $bars, $users, $hiddenGemBadge);

        // 6. Seed Recommendations (200)
        $this->seedRecommendations($spots, $users, $tenerife);

        // 7. Seed Reviews & Reactions (400)
        $this->seedReviews($spots, $users);

        // 8. Follow Network (100)
        $this->seedFollowNetwork($users);

        // 9. Campaign & Business Claims
        $this->seedCampaignAndClaims($tenerife, $foodDrinks, $restaurants, $users, $spots);
    }

    private function seedTenerifeAreas($region)
    {
        // Rely on LocationSeeder mostly, but add coordinates if missing
        $areas = Area::where('region_id', $region->id)->get();

        $coordinates = [
            'costa-adeje' => ['lat' => 28.0777, 'lng' => -16.7323],
            'los-cristianos' => ['lat' => 28.0531, 'lng' => -16.7164],
            'playa-de-las-americas' => ['lat' => 28.0594, 'lng' => -16.7297],
            'puerto-de-la-cruz' => ['lat' => 28.4124, 'lng' => -16.5448],
            'la-laguna' => ['lat' => 28.4853, 'lng' => -16.3201],
            'icod-de-los-vinos' => ['lat' => 28.3725, 'lng' => -16.7119],
        ];

        foreach ($areas as $area) {
            if (isset($coordinates[$area->slug])) {
                $area->update([
                    'latitude' => $coordinates[$area->slug]['lat'],
                    'longitude' => $coordinates[$area->slug]['lng'],
                ]);
            }
        }

        return $areas;
    }

    private function seedDiverseUsers($region, $communities)
    {
        $users = collect();

        // Active Locals (10)
        for ($i = 0; $i < 10; $i++) {
            $user = User::factory()->create([
                'community_id' => $communities->random()->id,
                'residence_region_id' => $region->id,
                'preferred_language' => fake()->randomElement(['en', 'es', 'nl']),
            ]);
            $this->seedUserStatus($user, $region, UserRegionStatusEnum::LOCAL, 85);
            $users->push($user);
        }

        // Tourists (15)
        for ($i = 0; $i < 15; $i++) {
            $user = User::factory()->create([
                'community_id' => $communities->random()->id,
                'residence_region_id' => null,
            ]);
            $this->seedUserStatus($user, $region, UserRegionStatusEnum::TOURIST, 10);
            $users->push($user);
        }

        // Regular Visitors (10)
        for ($i = 0; $i < 10; $i++) {
            $user = User::factory()->create();
            $this->seedUserStatus($user, $region, UserRegionStatusEnum::REGULAR_VISITOR, 60);
            $users->push($user);
        }

        // Experts / Residents (5)
        $experts = [
            ['name' => 'Jan Tenerife', 'lang' => 'nl', 'comm' => 'netherlands'],
            ['name' => 'Sofie Belgian Food', 'lang' => 'nl', 'comm' => 'belgium'],
            ['name' => 'Carlos Auténtico', 'lang' => 'es', 'comm' => 'spain-canaries'],
            ['name' => 'Mike Nightlife', 'lang' => 'en', 'comm' => 'united-kingdom'],
            ['name' => 'Helga Wandelt', 'lang' => 'de', 'comm' => 'germany'],
        ];

        foreach ($experts as $exp) {
            $user = User::updateOrCreate(['email' => Str::slug($exp['name']) . '@example.com'], [
                'name' => $exp['name'],
                'preferred_language' => $exp['lang'],
                'community_id' => Community::where('slug', $exp['comm'])->first()?->id,
                'email_verified_at' => now(),
            ]);
            $this->seedUserStatus($user, $region, UserRegionStatusEnum::VERIFIED_LOCAL, 98);
            $users->push($user);
        }

        return $users;
    }

    private function seedUserStatus($user, $region, $status, $score)
    {
        UserRegionStatus::create([
            'user_id' => $user->id,
            'region_id' => $region->id,
            'status' => $status,
            'confidence_score' => $score,
            'verified_at' => $status === UserRegionStatusEnum::VERIFIED_LOCAL ? now() : null,
            'claimed_by_user' => true,
            'residence_based' => $status === UserRegionStatusEnum::LOCAL,
        ]);

        CookieConsent::factory()->create(['user_id' => $user->id]);
        NotificationPreference::factory()->create(['user_id' => $user->id]);
    }

    private function seedRealisticSpots($region, $areas, $sector, $restaurantCat, $barCat, $users, $badge)
    {
        $spots = collect();

        // 60 Spots distributed
        foreach ($areas as $area) {
            $places = $area->places;
            $count = ($area->slug === 'costa-adeje' || $area->slug === 'playa-de-las-americas') ? 15 : 6;

            for ($i = 0; $i < $count; $i++) {
                $place = $places->random();
                $isBar = fake()->boolean(40);
                $isGem = fake()->boolean(15);
                $name = fake()->company();
                $lang = fake()->randomElement(['en', 'nl', 'es']);

                $spot = Spot::factory()->create([
                    'name' => [$lang => $name],
                    'slug' => Str::slug($name) . '-' . fake()->unique()->numberBetween(1000, 9999),
                    'region_id' => $region->id,
                    'area_id' => $area->id,
                    'place_id' => $place->id,
                    'sector_id' => $sector->id,
                    'category_id' => $isBar ? $barCat->id : $restaurantCat->id,
                    'latitude' => $area->latitude + (fake()->randomFloat(4, -0.01, 0.01)),
                    'longitude' => $area->longitude + (fake()->randomFloat(4, -0.01, 0.01)),
                    'created_by' => $users->random()->id,
                ]);

                if ($isGem) {
                    $spot->badges()->attach($badge->id);
                }

                // Add Community Profiles
                $this->seedSpotCommunityProfile($spot);
                $spots->push($spot);
            }
        }
        return $spots;
    }

    private function seedSpotCommunityProfile($spot)
    {
        $comms = Community::all();
        $total = 100;
        foreach ($comms as $index => $comm) {
            $percent = ($index === $comms->count() - 1) ? $total : rand(5, min($total, 40));
            $total -= $percent;
            SpotCommunityProfile::create([
                'spot_id' => $spot->id,
                'community_id' => $comm->id,
                'percentage' => $percent,
                'confidence_score' => rand(50, 95) / 100,
            ]);
            if ($total <= 0) break;
        }
    }

    private function seedRecommendations($spots, $users, $region)
    {
        $locals = $users->filter(fn($u) => $u->regionStatuses->where('status', UserRegionStatusEnum::LOCAL)->count() > 0
            || $u->regionStatuses->where('status', UserRegionStatusEnum::VERIFIED_LOCAL)->count() > 0);

        for ($i = 0; $i < 150; $i++) {
            $user = $locals->random();
            $spot = $spots->random();

            if (Recommendation::where('user_id', $user->id)->where('spot_id', $spot->id)->exists()) continue;

            $rec = Recommendation::factory()->create([
                'user_id' => $user->id,
                'spot_id' => $spot->id,
                'region_id' => $region->id,
                'community_id' => $user->community_id,
                'motivation' => [
                    'en' => fake()->sentence(20),
                    'es' => 'Recomendado por un local.',
                ]
            ]);

            TimelineEvent::create([
                'user_id' => $user->id,
                'type' => 'recommendation',
                'eventable_type' => Recommendation::class,
                'eventable_id' => $rec->id,
                'region_id' => $region->id,
                'payload' => ['spot_name' => $spot->getTranslation('name', 'en')],
            ]);
        }
    }

    private function seedReviews($spots, $users)
    {
        foreach ($spots as $spot) {
            $reviewCount = rand(3, 10);
            for ($i = 0; $i < $reviewCount; $i++) {
                $user = $users->random();
                $review = Review::factory()->create([
                    'user_id' => $user->id,
                    'spot_id' => $spot->id,
                ]);

                // Reactions
                $reactionCount = rand(0, 5);
                for ($j = 0; $j < $reactionCount; $j++) {
                    ReviewReaction::create([
                        'user_id' => $users->random()->id,
                        'review_id' => $review->id,
                        'reaction' => fake()->randomElement(ReviewReactionType::cases()),
                    ]);
                }

                // Visits
                if (fake()->boolean(30)) {
                    SpotVisit::factory()->create([
                        'user_id' => $user->id,
                        'spot_id' => $spot->id,
                    ]);
                }
            }
        }
    }

    private function seedFollowNetwork($users)
    {
        for ($i = 0; $i < 100; $i++) {
            $follower = $users->random();
            $followed = $users->random();
            if ($follower->id === $followed->id) continue;

            Follow::firstOrCreate([
                'follower_id' => $follower->id,
                'followed_id' => $followed->id,
            ]);
        }
    }

    private function seedCampaignAndClaims($region, $sector, $category, $users, $spots)
    {
        $campaign = Campaign::updateOrCreate(['slug' => 'tafelen-in-tenerife'], [
            'name' => ['en' => 'Tafelen in Tenerife', 'nl' => 'Tafelen in Tenerife'],
            'description' => ['en' => 'The best restaurants in Tenerife.'],
            'region_id' => $region->id,
            'sector_id' => $sector->id,
            'category_id' => $category->id,
            'is_active' => true,
        ]);

        for ($i = 0; $i < 30; $i++) {
            CampaignSubmission::factory()->create([
                'campaign_id' => $campaign->id,
                'user_id' => $users->random()->id,
                'matched_spot_id' => $spots->random()->id,
            ]);
        }

        // Claims
        foreach ($spots->take(5) as $spot) {
            SpotClaim::factory()->create([
                'spot_id' => $spot->id,
                'user_id' => $users->random()->id,
                'status' => 'approved',
            ]);
            $spot->update(['is_claimed' => true, 'claimed_at' => now()]);
        }
    }
}
