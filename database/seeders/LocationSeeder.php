<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Place;
use App\Models\Region;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $region = Region::updateOrCreate(
            ['slug' => 'tenerife'],
            [
                'name' => [
                    'nl' => 'Tenerife',
                    'en' => 'Tenerife',
                    'es' => 'Tenerife',
                    'de' => 'Tenerife',
                    'fr' => 'Tenerife',
                ],
                'latitude' => 28.2915637,
                'longitude' => -16.6291304,
                'is_active' => true,
            ]
        );

        $areas = [
            'costa-adeje' => [
                'name' => ['nl' => 'Costa Adeje', 'en' => 'Costa Adeje', 'es' => 'Costa Adeje'],
                'places' => [
                    'puerto-colon' => ['nl' => 'Puerto Colón', 'en' => 'Puerto Colón', 'es' => 'Puerto Colón'],
                    'fanabe' => ['nl' => 'Fañabé', 'en' => 'Fañabé', 'es' => 'Fañabé'],
                    'la-caleta' => ['nl' => 'La Caleta', 'en' => 'La Caleta', 'es' => 'La Caleta'],
                    'playa-del-duque' => ['nl' => 'Playa del Duque', 'en' => 'Playa del Duque', 'es' => 'Playa del Duque'],
                ]
            ],
            'los-cristianos' => [
                'name' => ['nl' => 'Los Cristianos', 'en' => 'Los Cristianos', 'es' => 'Los Cristianos'],
                'places' => [
                    'centro' => ['nl' => 'Centro', 'en' => 'Downtown', 'es' => 'Centro'],
                    'playa-de-los-cristianos' => ['nl' => 'Playa de Los Cristianos', 'en' => 'Los Cristianos Beach', 'es' => 'Playa de Los Cristianos'],
                    'la-camison' => ['nl' => 'La Camisón', 'en' => 'La Camisón', 'es' => 'La Camisón'],
                ]
            ],
            'playa-de-las-americas' => [
                'name' => ['nl' => 'Playa de las Américas', 'en' => 'Playa de las Américas', 'es' => 'Playa de las Américas'],
                'places' => [
                    'golden-mile' => ['nl' => 'Golden Mile', 'en' => 'Golden Mile', 'es' => 'Milla de Oro'],
                    'las-veronicas' => ['nl' => 'Las Verónicas', 'en' => 'Las Verónicas', 'es' => 'Las Verónicas'],
                ]
            ],
            'la-laguna' => [
                'name' => ['nl' => 'La Laguna', 'en' => 'La Laguna', 'es' => 'La Laguna'],
                'places' => []
            ],
            'puerto-de-la-cruz' => [
                'name' => ['nl' => 'Puerto de la Cruz', 'en' => 'Puerto de la Cruz', 'es' => 'Puerto de la Cruz'],
                'places' => []
            ],
            'icod-de-los-vinos' => [
                'name' => ['nl' => 'Icod de los Vinos', 'en' => 'Icod de los Vinos', 'es' => 'Icod de los Vinos'],
                'places' => []
            ],
        ];

        foreach ($areas as $areaSlug => $areaData) {
            $area = Area::updateOrCreate(
                ['slug' => $areaSlug],
                [
                    'region_id' => $region->id,
                    'name' => $areaData['name'],
                    'is_active' => true,
                ]
            );

            foreach ($areaData['places'] as $placeSlug => $placeName) {
                Place::updateOrCreate(
                    ['slug' => $placeSlug],
                    [
                        'area_id' => $area->id,
                        'name' => $placeName,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
