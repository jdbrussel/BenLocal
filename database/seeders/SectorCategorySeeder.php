<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Sector;
use Illuminate\Database\Seeder;

class SectorCategorySeeder extends Seeder
{
    public function run(): void
    {
        $sector = Sector::updateOrCreate(
            ['slug' => 'food-drinks'],
            [
                'name' => [
                    'nl' => 'Eten & Drinken',
                    'en' => 'Food & Drinks',
                    'es' => 'Comida y Bebida',
                ],
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        $categories = [
            [
                'slug' => 'restaurants',
                'name' => [
                    'nl' => 'Restaurants',
                    'en' => 'Restaurants',
                    'es' => 'Restaurantes',
                ],
                'sort_order' => 1,
            ],
            [
                'slug' => 'bars',
                'name' => [
                    'nl' => 'Bars',
                    'en' => 'Bars',
                    'es' => 'Bares',
                ],
                'sort_order' => 2,
            ],
        ];

        foreach ($categories as $data) {
            Category::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'sector_id' => $sector->id,
                    'name' => $data['name'],
                    'sort_order' => $data['sort_order'],
                    'is_active' => true,
                ]
            );
        }
    }
}
