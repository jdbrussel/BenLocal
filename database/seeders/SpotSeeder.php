<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Category;
use App\Models\Community;
use App\Models\Place;
use App\Models\Region;
use App\Models\Sector;
use App\Models\Spot;
use App\Models\SpotBadge;
use App\Models\SpotBadgeAssignment;
use App\Models\SpotCommunityProfile;
use App\Models\User;
use Illuminate\Database\Seeder;

class SpotSeeder extends Seeder
{
    public function run(): void
    {
        $foodDrinks = Sector::where('slug', 'food-drinks')->first();
        $restaurants = Category::where('slug', 'restaurants')->first();
        $bars = Category::where('slug', 'bars')->first();
        $tenerife = Region::where('slug', 'tenerife')->first();

        // Badges
        $badgesData = [
            'hidden-gem' => ['nl' => 'Hidden Gem', 'en' => 'Hidden Gem'],
            'local-favourite' => ['nl' => 'Local Favourite', 'en' => 'Local Favourite'],
            'trusted-by-locals' => ['nl' => 'Trusted by Locals', 'en' => 'Trusted by Locals'],
            'canarian-favourite' => ['nl' => 'Canarische Favoriet', 'en' => 'Canarian Favourite'],
            'dutch-favourite' => ['nl' => 'Nederlandse Favoriet', 'en' => 'Dutch Favourite'],
            'belgian-favourite' => ['nl' => 'Belgische Favoriet', 'en' => 'Belgian Favourite'],
            'international-quality' => ['nl' => 'Internationale Kwaliteit', 'en' => 'International Quality'],
            'tourist-approved' => ['nl' => 'Tourist Approved', 'en' => 'Tourist Approved'],
            'rising-spot' => ['nl' => 'Rising Spot', 'en' => 'Rising Spot'],
            'tfs-selection' => ['nl' => 'TFS Selection', 'en' => 'TFS Selection'],
        ];

        $badges = [];
        foreach ($badgesData as $key => $name) {
            $badges[$key] = SpotBadge::updateOrCreate(['key' => $key], ['name' => $name]);
        }

        $spots = [
            [
                'slug' => 'bodega-san-miguel',
                'name' => ['nl' => 'Bodega San Miguel', 'en' => 'Bodega San Miguel'],
                'category_id' => $restaurants->id,
                'area_slug' => 'costa-adeje',
                'place_slug' => 'la-caleta',
                'spec_values' => [
                    'venue_type' => ['bodega'],
                    'cuisine' => ['canarian', 'spanish'],
                    'price_class' => '€€',
                    'terrace' => true,
                    'sea_view' => false,
                ],
                'profiles' => [
                    'spain-canaries' => 80,
                    'netherlands' => 10,
                    'belgium' => 5,
                    'other' => 5,
                ],
                'badges' => ['trusted-by-locals'],
                'is_claimed' => true,
                'verified_business' => true,
            ],
            [
                'slug' => 'guachinche-casa-pepe',
                'name' => ['nl' => 'Guachinche Casa Pepe', 'en' => 'Guachinche Casa Pepe'],
                'category_id' => $restaurants->id,
                'area_slug' => 'icod-de-los-vinos',
                'spec_values' => [
                    'venue_type' => ['guachinche'],
                    'cuisine' => ['canarian'],
                    'price_class' => '€',
                    'terrace' => true,
                ],
                'badges' => ['hidden-gem', 'canarian-favourite'],
                'lifecycle_status' => 'active',
            ],
            [
                'slug' => 'asador-el-camino',
                'name' => ['nl' => 'Asador El Camino', 'en' => 'Asador El Camino'],
                'category_id' => $restaurants->id,
                'area_slug' => 'los-cristianos',
                'spec_values' => [
                    'venue_type' => ['asador'],
                    'cuisine' => ['grill', 'spanish'],
                    'price_class' => '€€',
                ],
                'is_claimed' => false,
            ],
            [
                'slug' => 'restaurante-mar-azul',
                'name' => ['nl' => 'Restaurante Mar Azul', 'en' => 'Restaurante Mar Azul'],
                'category_id' => $restaurants->id,
                'area_slug' => 'costa-adeje',
                'place_slug' => 'puerto-colon',
                'spec_values' => [
                    'venue_type' => ['seafood'],
                    'cuisine' => ['seafood', 'international'],
                    'price_class' => '€€€',
                ],
                'badges' => ['international-quality'],
                'is_claimed' => true,
                'verified_business' => false,
            ],
            [
                'slug' => 'la-mesa-flamenca',
                'name' => ['nl' => 'La Mesa Flamenca', 'en' => 'La Mesa Flamenca'],
                'category_id' => $restaurants->id,
                'area_slug' => 'playa-de-las-americas',
                'spec_values' => [
                    'venue_type' => ['tapas'],
                    'cuisine' => ['spanish'],
                    'price_class' => '€€',
                ],
            ],
            [
                'slug' => 'belgian-bistro-tenerife',
                'name' => ['nl' => 'Belgian Bistro Tenerife', 'en' => 'Belgian Bistro Tenerife'],
                'category_id' => $restaurants->id,
                'area_slug' => 'los-cristianos',
                'spec_values' => [
                    'venue_type' => ['international'],
                    'cuisine' => ['belgian'],
                    'price_class' => '€€',
                ],
                'profiles' => [
                    'belgium' => 60,
                    'netherlands' => 25,
                    'united-kingdom' => 10,
                    'other' => 5,
                ],
            ],
            [
                'slug' => 'puerto-beach-bar',
                'name' => ['nl' => 'Puerto Beach Bar', 'en' => 'Puerto Beach Bar'],
                'category_id' => $bars->id,
                'area_slug' => 'costa-adeje',
                'place_slug' => 'puerto-colon',
                'spec_values' => [
                    'venue_type' => ['beachbar'],
                    'drink_focus' => ['cocktails', 'beer'],
                    'sea_view' => true,
                ],
                'badges' => ['tourist-approved'],
            ],
            [
                'slug' => 'la-noche-cocktail-lounge',
                'name' => ['nl' => 'La Noche Cocktail Lounge', 'en' => 'La Noche Cocktail Lounge'],
                'category_id' => $bars->id,
                'area_slug' => 'playa-de-las-americas',
                'spec_values' => [
                    'venue_type' => ['cocktailbar'],
                    'drink_focus' => ['cocktails', 'gin_tonic'],
                    'open_late' => true,
                ],
            ],
            [
                'slug' => 'cafe-vlaanderen',
                'name' => ['nl' => 'Café Vlaanderen', 'en' => 'Café Vlaanderen'],
                'category_id' => $bars->id,
                'area_slug' => 'los-cristianos',
                'spec_values' => [
                    'venue_type' => ['eetcafe', 'bierbar'],
                    'drink_focus' => ['beer', 'tapas'],
                ],
                'profiles' => [
                    'belgium' => 65,
                    'netherlands' => 20,
                    'spain-canaries' => 10,
                    'other' => 5,
                ],
                'badges' => ['belgian-favourite'],
                'is_claimed' => true,
                'verified_business' => true,
            ],
            [
                'slug' => 'bodega-del-puerto',
                'name' => ['nl' => 'Bodega del Puerto', 'en' => 'Bodega del Puerto'],
                'category_id' => $bars->id,
                'area_slug' => 'costa-adeje',
                'place_slug' => 'puerto-colon',
                'spec_values' => [
                    'venue_type' => ['wijnbar'],
                    'drink_focus' => ['wine', 'tapas'],
                ],
            ],
        ];

        foreach ($spots as $data) {
            $area = Area::where('slug', $data['area_slug'])->first();
            $place = isset($data['place_slug']) ? Place::where('slug', $data['place_slug'])->first() : null;

            $spot = Spot::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'name' => $data['name'],
                    'sector_id' => $foodDrinks->id,
                    'category_id' => $data['category_id'],
                    'region_id' => $tenerife->id,
                    'area_id' => $area?->id,
                    'place_id' => $place?->id,
                    'spec_values' => $data['spec_values'],
                    'lifecycle_status' => $data['lifecycle_status'] ?? 'active',
                    'is_claimed' => $data['is_claimed'] ?? false,
                    'verified_business' => $data['verified_business'] ?? false,
                    'description' => [
                        'nl' => 'Dit is een realistische demo beschrijving voor ' . $data['name']['nl'],
                        'en' => 'This is a realistic demo description for ' . $data['name']['en'],
                    ],
                ]
            );

            // Community Profiles
            if (isset($data['profiles'])) {
                foreach ($data['profiles'] as $communitySlug => $percentage) {
                    $community = Community::where('slug', $communitySlug)->first();
                    if ($community) {
                        SpotCommunityProfile::updateOrCreate(
                            ['spot_id' => $spot->id, 'community_id' => $community->id],
                            ['percentage' => $percentage]
                        );
                    }
                }
            }

            // Badges
            if (isset($data['badges'])) {
                foreach ($data['badges'] as $badgeKey) {
                    SpotBadgeAssignment::firstOrCreate([
                        'spot_id' => $spot->id,
                        'badge_id' => $badges[$badgeKey]->id,
                    ]);
                }
            }
        }
    }
}
