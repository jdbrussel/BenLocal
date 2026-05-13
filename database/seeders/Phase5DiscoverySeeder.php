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
use App\Models\SavedSpot;
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
use Illuminate\Support\Facades\DB;

class Phase5DiscoverySeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info("Starting Phase5DiscoverySeeder...");

        // 1. Core Data Lookups
        $tenerife = Region::where('slug', 'tenerife')->first();
        if (!$tenerife) {
            $this->command->warn("Tenerife region not found. Skipping Phase5DiscoverySeeder.");
            return;
        }

        $communities = Community::all();
        if ($communities->isEmpty()) {
            $this->command->warn("No communities found. Skipping Phase5DiscoverySeeder.");
            return;
        }

        $foodDrinks = Sector::where('slug', 'food-drinks')->first();
        $restaurants = Category::where('slug', 'restaurants')->first();
        $bars = Category::where('slug', 'bars')->first();

        if (!$foodDrinks || !$restaurants || !$bars) {
            $this->command->warn("Required sectors/categories (food-drinks, restaurants, bars) missing.");
        }

        $largeDemo = env('BENLOCAL_LARGE_DEMO_DATA', false);
        $this->command->info($largeDemo ? "Using LARGE demo data settings." : "Using dev-safe (SMALL) data settings.");

        // 2. Expand Tenerife Areas & Places
        $this->command->info("Expanding Tenerife locations...");
        $areas = $this->expandTenerifeLocations($tenerife);

        // 3. Badges
        $hiddenGemBadge = SpotBadge::where('key', 'hidden-gem')->first() ?? SpotBadge::create([
            'key' => 'hidden-gem',
            'name' => ['en' => 'Hidden Gem', 'nl' => 'Verborgen Parel', 'es' => 'Joya Oculta'],
            'description' => ['en' => 'Locals love this place, but tourists haven\'t found it yet.'],
            'icon' => 'gem',
            'color' => 'purple',
        ]);

        // 4. Seed More Users
        $userCount = $largeDemo ? 60 : 25;
        $this->command->info("Seeding $userCount more users...");
        $users = $this->seedMoreUsers($tenerife, $communities, $userCount);

        // 5. Seed Realistic Spots
        $this->command->info("Seeding discovery spots...");
        $spots = $this->seedDiscoverySpots($tenerife, $areas, $foodDrinks, $restaurants, $bars, $users, $hiddenGemBadge, $largeDemo);

        // 6. Seed Recommendations
        $recTarget = $largeDemo ? 300 : 80;
        $this->command->info("Seeding recommendations...");
        $this->seedRecommendations($spots, $users, $tenerife, $recTarget);

        // 7. Seed Reviews & Reactions
        $this->command->info("Seeding reviews and reactions...");
        $this->seedReviewsAndReactions($spots, $users, $largeDemo);

        // 8. Saved Spots
        $saveTarget = $largeDemo ? 400 : 80;
        $this->command->info("Seeding saved spots...");
        $this->seedSavedSpots($spots, $users, $saveTarget);

        // 9. Follow Network
        $followTarget = $largeDemo ? 200 : 60;
        $this->command->info("Seeding follow network...");
        $this->seedFollowNetwork($users, $followTarget);

        // 10. Timeline Events Expansion
        $this->command->info("Seeding timeline events...");
        $this->seedTimelineEvents($users, $spots, $tenerife, $largeDemo);

        // 11. Campaigns expansion
        $this->command->info("Seeding campaign activity...");
        $this->seedCampaignActivity($tenerife, $foodDrinks, $restaurants, $users, $spots, $largeDemo);

        $this->command->info("Phase5DiscoverySeeder completed.");
    }

    private function expandTenerifeLocations($region)
    {
        $additionalLocations = [
            'el-medano' => [
                'name' => ['en' => 'El Médano', 'es' => 'El Médano'],
                'lat' => 28.0456, 'lng' => -16.5361,
                'places' => [
                    'playa-chica' => ['en' => 'Playa Chica'],
                    'la-tejita' => ['en' => 'La Tejita'],
                    'town-center' => ['en' => 'Town Center'],
                ]
            ],
            'garachico' => [
                'name' => ['en' => 'Garachico', 'es' => 'Garachico'],
                'lat' => 28.3732, 'lng' => -16.7647,
                'places' => [
                    'natural-pools' => ['en' => 'Natural Pools'],
                    'historic-center' => ['en' => 'Historic Center'],
                ]
            ],
            'adeje-village' => [
                'name' => ['en' => 'Adeje Village', 'es' => 'Adeje Casco'],
                'lat' => 28.1202, 'lng' => -16.7261,
                'places' => [
                    'barranco-del-infierno' => ['en' => 'Barranco del Infierno'],
                ]
            ],
            'la-caleta' => [
                'name' => ['en' => 'La Caleta', 'es' => 'La Caleta'],
                'lat' => 28.0988, 'lng' => -16.7538,
                'places' => [
                    'turtle-bay' => ['en' => 'Turtle Bay'],
                    'seafront' => ['en' => 'Seafront'],
                ]
            ]
        ];

        foreach ($additionalLocations as $slug => $data) {
            $area = Area::updateOrCreate(['slug' => $slug], [
                'region_id' => $region->id,
                'name' => $data['name'],
                'latitude' => $data['lat'],
                'longitude' => $data['lng'],
                'is_active' => true,
            ]);

            foreach ($data['places'] as $pSlug => $pName) {
                Place::updateOrCreate(['slug' => $pSlug], [
                    'area_id' => $area->id,
                    'name' => $pName,
                    'is_active' => true,
                ]);
            }
        }

        // Add coordinates to existing ones from LocationSeeder if not set
        $existing = [
            'costa-adeje' => ['lat' => 28.0777, 'lng' => -16.7323],
            'los-cristianos' => ['lat' => 28.0531, 'lng' => -16.7164],
            'playa-de-las-americas' => ['lat' => 28.0594, 'lng' => -16.7297],
            'puerto-de-la-cruz' => ['lat' => 28.4124, 'lng' => -16.5448],
            'la-laguna' => ['lat' => 28.4853, 'lng' => -16.3201],
            'icod-de-los-vinos' => ['lat' => 28.3725, 'lng' => -16.7119],
        ];

        foreach ($existing as $slug => $coords) {
            Area::where('slug', $slug)->update(['latitude' => $coords['lat'], 'longitude' => $coords['lng']]);
        }

        return Area::where('region_id', $region->id)->get();
    }

    private function seedMoreUsers($region, $communities, $count)
    {
        $newUsers = User::factory()->count($count)->create();

        foreach ($newUsers as $user) {
            $status = fake()->randomElement(UserRegionStatusEnum::cases());
            $score = rand(10, 95);

            UserRegionStatus::create([
                'user_id' => $user->id,
                'region_id' => $region->id,
                'status' => $status,
                'confidence_score' => $score,
                'verified_at' => $status === UserRegionStatusEnum::VERIFIED_LOCAL ? now() : null,
                'claimed_by_user' => true,
            ]);

            CookieConsent::factory()->create(['user_id' => $user->id]);
            NotificationPreference::factory()->create(['user_id' => $user->id]);
        }

        return User::all();
    }

    private function seedDiscoverySpots($region, $areas, $sector, $restaurantCat, $barCat, $users, $badge, $largeDemo)
    {
        $spots = collect();
        if (!$sector || !$restaurantCat || !$barCat) return $spots;

        $types = [
            'guachinche' => ['cat' => $restaurantCat, 'cuisines' => ['canarian'], 'price' => '€'],
            'beachbar' => ['cat' => $barCat, 'cuisines' => ['international'], 'price' => '€€'],
            'fine-dining' => ['cat' => $restaurantCat, 'cuisines' => ['spanish', 'international'], 'price' => '€€€€'],
            'tapas' => ['cat' => $restaurantCat, 'cuisines' => ['spanish', 'canarian'], 'price' => '€€'],
            'belgian-cafe' => ['cat' => $barCat, 'cuisines' => ['belgian'], 'price' => '€€'],
            'seafood' => ['cat' => $restaurantCat, 'cuisines' => ['canarian', 'international'], 'price' => '€€€'],
        ];

        foreach ($areas as $area) {
            $places = $area->places;
            if ($places->isEmpty()) continue;

            $count = match($area->slug) {
                'costa-adeje', 'playa-de-las-americas', 'los-cristianos' => $largeDemo ? 20 : 8,
                'el-medano', 'la-caleta' => $largeDemo ? 12 : 5,
                default => $largeDemo ? 6 : 3
            };

            for ($i = 0; $i < $count; $i++) {
                $place = $places->random();
                $typeKey = array_rand($types);
                $config = $types[$typeKey];

                $name = fake()->company();
                $isGem = fake()->boolean(15);

                $spot = Spot::factory()->create([
                    'name' => ['en' => $name, 'es' => $name . ' (Local)'],
                    'slug' => Str::slug($name) . '-' . Str::random(5),
                    'region_id' => $region->id,
                    'area_id' => $area->id,
                    'place_id' => $place->id,
                    'sector_id' => $sector->id,
                    'category_id' => $config['cat']->id,
                    'latitude' => $area->latitude + (fake()->randomFloat(5, -0.015, 0.015)),
                    'longitude' => $area->longitude + (fake()->randomFloat(5, -0.015, 0.015)),
                    'spec_values' => [
                        'venue_type' => [$typeKey],
                        'cuisine' => $config['cuisines'],
                        'price_class' => $config['price'],
                        'terrace' => fake()->boolean(80),
                        'sea_view' => $area->slug === 'el-medano' || $area->slug === 'la-caleta' || fake()->boolean(30),
                        'open_late' => $config['cat']->slug === 'bars' || fake()->boolean(20),
                    ],
                    'created_by' => $users->random()->id,
                ]);

                if ($isGem) {
                    $spot->badges()->attach($badge->id);
                }

                $this->seedSpotCommunityProfile($spot, $typeKey);
                $spots->push($spot);
            }
        }
        return $spots;
    }

    private function seedSpotCommunityProfile($spot, $type)
    {
        $comms = Community::all();

        foreach ($comms as $comm) {
            $percentage = 10;
            if ($type === 'belgian-cafe' && $comm->slug === 'belgium') $percentage = 65;
            if ($type === 'belgian-cafe' && $comm->slug === 'netherlands') $percentage = 20;
            if ($type === 'guachinche' && $comm->slug === 'spain-canaries') $percentage = 85;

            SpotCommunityProfile::updateOrCreate([
                'spot_id' => $spot->id,
                'community_id' => $comm->id,
            ], [
                'percentage' => $percentage,
                'confidence_score' => rand(60, 98) / 100,
            ]);
        }
    }

    private function seedRecommendations($spots, $users, $region, $target)
    {
        $locals = $users->filter(fn($u) => in_array($u->regionStatuses->where('region_id', $region->id)->first()?->status, [
            UserRegionStatusEnum::LOCAL,
            UserRegionStatusEnum::VERIFIED_LOCAL
        ]));

        if ($locals->isEmpty()) $locals = $users->take(10);
        if ($spots->isEmpty() || $locals->isEmpty()) return;

        $created = 0;
        $attempts = 0;
        $maxAttempts = $target * 2;

        while ($created < $target && $attempts < $maxAttempts) {
            $attempts++;
            $user = $locals->random();
            $spot = $spots->random();

            if (Recommendation::where('user_id', $user->id)->where('spot_id', $spot->id)->exists()) continue;

            Recommendation::factory()->create([
                'user_id' => $user->id,
                'spot_id' => $spot->id,
                'region_id' => $region->id,
                'community_id' => $user->community_id ?? Community::first()->id,
                'motivation' => [
                    'en' => fake()->sentence(15),
                    'es' => 'Auténtica experiencia local.',
                    'nl' => 'Echt een aanrader voor de community.'
                ]
            ]);
            $created++;
        }
        $this->command->info("Created $created recommendations ($attempts attempts).");
    }

    private function seedReviewsAndReactions($spots, $users, $largeDemo)
    {
        foreach ($spots as $spot) {
            $count = $largeDemo ? rand(5, 12) : rand(2, 5);
            $reviews = Review::factory()->count($count)->create([
                'spot_id' => $spot->id,
                'user_id' => fn() => $users->random()->id,
            ]);

            foreach ($reviews as $review) {
                // Reactions
                $reactCount = $largeDemo ? rand(2, 6) : rand(0, 3);
                for ($j = 0; $j < $reactCount; $j++) {
                    $reactUser = $users->random();
                    ReviewReaction::updateOrCreate([
                        'user_id' => $reactUser->id,
                        'review_id' => $review->id,
                    ], [
                        'reaction' => fake()->randomElement(ReviewReactionType::cases()),
                    ]);
                }

                if (fake()->boolean(30)) {
                    SpotVisit::factory()->create([
                        'user_id' => $review->user_id,
                        'spot_id' => $spot->id,
                        'checked_in_at' => $review->visited_at,
                    ]);
                }
            }
        }
    }

    private function seedSavedSpots($spots, $users, $target)
    {
        if ($spots->isEmpty() || $users->isEmpty()) return;
        for ($i = 0; $i < $target; $i++) {
            $user = $users->random();
            $spot = $spots->random();

            SavedSpot::firstOrCreate([
                'user_id' => $user->id,
                'spot_id' => $spot->id,
            ]);
        }
    }

    private function seedFollowNetwork($users, $target)
    {
        if ($users->count() < 2) return;
        for ($i = 0; $i < $target; $i++) {
            $follower = $users->random();
            $followed = $users->random();
            if ($follower->id === $followed->id) continue;

            Follow::firstOrCreate([
                'follower_id' => $follower->id,
                'followed_id' => $followed->id,
            ]);
        }
    }

    private function seedTimelineEvents($users, $spots, $region, $largeDemo)
    {
        if ($users->isEmpty() || $spots->isEmpty()) return;
        $userSub = $users->take($largeDemo ? 40 : 15);
        $count = 0;
        foreach ($userSub as $user) {
            $events = $largeDemo ? 5 : 3;
            for ($i = 0; $i < $events; $i++) {
                $spot = $spots->random();
                TimelineEvent::create([
                    'user_id' => $user->id,
                    'region_id' => $region->id,
                    'type' => fake()->randomElement(['recommendation', 'review', 'follow', 'saved_spot']),
                    'eventable_type' => Spot::class,
                    'eventable_id' => $spot->id,
                    'payload' => ['name' => $spot->getTranslation('name', 'en')],
                    'created_at' => fake()->dateTimeBetween('-1 month', 'now'),
                ]);
                $count++;
            }
        }
        $this->command->info("Created $count timeline events.");
    }

    private function seedCampaignActivity($region, $sector, $category, $users, $spots, $largeDemo)
    {
        $campaign = Campaign::where('slug', 'tafelen-in-tenerife')->first();
        if (!$campaign) {
            $this->command->warn("Campaign 'tafelen-in-tenerife' not found. Skipping campaign activity.");
            return;
        }

        $target = $largeDemo ? 70 : 20;
        for ($i = 0; $i < $target; $i++) {
            CampaignSubmission::create([
                'campaign_id' => $campaign->id,
                'user_id' => $users->random()->id,
                'submitted_name' => fake()->company(),
                'matched_spot_id' => fake()->boolean(70) ? $spots->random()->id : null,
                'status' => fake()->randomElement(['pending', 'approved', 'rejected', 'shortlisted']),
            ]);
        }
        $this->command->info("Created $target campaign submissions.");
    }
}
