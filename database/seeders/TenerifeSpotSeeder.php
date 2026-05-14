<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Category;
use App\Models\Community;
use App\Models\Place;
use App\Models\Region;
use App\Models\Sector;
use App\Models\Spot;
use App\Models\SpotCommunityProfile;
use App\Enums\SpotLifecycleStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TenerifeSpotSeeder extends Seeder
{
    public function run(): void
    {
        $region = Region::where('slug', 'tenerife')->first();
        $sector = Sector::where('slug', 'food-drinks')->first();
        $catRestaurant = Category::where('slug', 'restaurants')->first();
        $catBar = Category::where('slug', 'bars')->first();

        $dutchCommunity = Community::where('slug', 'netherlands')->first();
        $belgianCommunity = Community::where('slug', 'belgium')->first();
        $britishCommunity = Community::where('slug', 'united-kingdom')->first();
        $localCommunity = Community::where('slug', 'spain-canaries')->first();

        $spots = [
            [
                'name' => ['en' => 'The Corner Bar', 'nl' => 'The Corner Bar', 'es' => 'The Corner Bar'],
                'description' => [
                    'en' => 'Popular local spot in Los Cristianos with great atmosphere and live sports.',
                    'nl' => 'Populaire lokale plek in Los Cristianos met een geweldige sfeer en live sport.',
                    'es' => 'Lugar local popular en Los Cristianos con gran ambiente y deportes en vivo.'
                ],
                'category_id' => $catBar->id,
                'area_slug' => 'los-cristianos',
                'place_slug' => 'centro',
                'address' => ['street' => 'Calle General Franco', 'number' => '12', 'city' => 'Los Cristianos'],
                'communities' => [
                    ['id' => $britishCommunity->id, 'pct' => 60],
                    ['id' => $localCommunity->id, 'pct' => 20],
                    ['id' => $dutchCommunity->id, 'pct' => 10],
                ]
            ],
            [
                'name' => ['en' => 'Restaurant El Cine', 'nl' => 'Restaurant El Cine', 'es' => 'Restaurante El Cine'],
                'description' => [
                    'en' => 'Famous for its fresh fish and traditional Canarian atmosphere near the harbor.',
                    'nl' => 'Beroemd om zijn verse vis en traditionele Canarische sfeer nabij de haven.',
                    'es' => 'Famoso por su pescado fresco y ambiente tradicional canario cerca del muelle.'
                ],
                'category_id' => $catRestaurant->id,
                'area_slug' => 'los-cristianos',
                'place_slug' => 'playa-de-los-cristianos',
                'address' => ['street' => 'Calle Juan Reveron Sierra', 'number' => '7', 'city' => 'Los Cristianos'],
                'communities' => [
                    ['id' => $localCommunity->id, 'pct' => 50],
                    ['id' => $britishCommunity->id, 'pct' => 20],
                    ['id' => $dutchCommunity->id, 'pct' => 15],
                ]
            ],
            [
                'name' => ['en' => 'Empire Modern British Restaurant', 'nl' => 'Empire Modern British Restaurant', 'es' => 'Empire Modern British Restaurant'],
                'description' => [
                    'en' => 'Upscale dining experience on the Golden Mile with a modern twist on British classics.',
                    'nl' => 'Luxe dinerervaring op de Golden Mile met een moderne draai aan Britse klassiekers.',
                    'es' => 'Experiencia gastronómica de lujo en la Milla de Oro con un toque moderno en los clásicos británicos.'
                ],
                'category_id' => $catRestaurant->id,
                'area_slug' => 'playa-de-las-americas',
                'place_slug' => 'golden-mile',
                'address' => ['street' => 'Avenida de las Américas', 'number' => 'S/N', 'city' => 'Playa de las Américas'],
                'communities' => [
                    ['id' => $britishCommunity->id, 'pct' => 80],
                    ['id' => $otherCommunityId ?? null, 'pct' => 20],
                ]
            ],
            [
                'name' => ['en' => 'Baobab', 'nl' => 'Baobab', 'es' => 'Baobab'],
                'description' => [
                    'en' => 'Stylish beach club and restaurant in Costa Adeje, perfect for sunset drinks.',
                    'nl' => 'Stijlvolle beachclub en restaurant in Costa Adeje, perfect voor drankjes bij zonsondergang.',
                    'es' => 'Elegante club de playa y restaurante en Costa Adeje, perfecto para tomar algo al atardecer.'
                ],
                'category_id' => $catRestaurant->id,
                'area_slug' => 'costa-adeje',
                'place_slug' => 'playa-del-duque',
                'address' => ['street' => 'Avenida de Bruselas', 'number' => 'S/N', 'city' => 'Costa Adeje'],
                'communities' => [
                    ['id' => $dutchCommunity->id, 'pct' => 30],
                    ['id' => $belgianCommunity->id, 'pct' => 30],
                    ['id' => $britishCommunity->id, 'pct' => 20],
                ]
            ],
        ];

        foreach ($spots as $spotData) {
            $area = Area::where('slug', $spotData['area_slug'])->first();
            $place = Place::where('slug', $spotData['place_slug'])->first();

            $spot = Spot::updateOrCreate(
                ['slug' => Str::slug($spotData['name']['en'])],
                [
                    'name' => $spotData['name'],
                    'description' => $spotData['description'],
                    'sector_id' => $sector->id,
                    'category_id' => $spotData['category_id'],
                    'region_id' => $region->id,
                    'area_id' => $area?->id,
                    'place_id' => $place?->id,
                    'address' => $spotData['address'],
                    'lifecycle_status' => SpotLifecycleStatus::ACTIVE,
                    'original_language' => 'en',
                    'is_claimed' => false,
                    'ai_enriched' => true,
                ]
            );

            foreach ($spotData['communities'] as $commData) {
                if ($commData['id']) {
                    SpotCommunityProfile::updateOrCreate(
                        ['spot_id' => $spot->id, 'community_id' => $commData['id']],
                        ['percentage' => $commData['pct'], 'source' => 'manual_seed']
                    );
                }
            }
        }
    }
}
