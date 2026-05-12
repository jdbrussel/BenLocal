<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BenLocalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Communities
        $communities = [
            'Spain / Canaries',
            'Netherlands',
            'Belgium',
            'Germany',
            'United Kingdom',
            'Other',
        ];

        foreach ($communities as $index => $cName) {
            \App\Models\Community::create([
                'name' => ['en' => $cName],
                'slug' => \Illuminate\Support\Str::slug($cName),
                'sort_order' => $index,
                'is_active' => true,
            ]);
        }

        // 2. Region / Area / Place
        $tenerife = \App\Models\Region::create([
            'name' => ['en' => 'Tenerife', 'es' => 'Tenerife'],
            'slug' => 'tenerife',
            'is_active' => true,
        ]);

        $costaAdeje = \App\Models\Area::create([
            'region_id' => $tenerife->id,
            'name' => ['en' => 'Costa Adeje', 'es' => 'Costa Adeje'],
            'slug' => 'costa-adeje',
            'is_active' => true,
        ]);

        $puertoColon = \App\Models\Place::create([
            'area_id' => $costaAdeje->id,
            'name' => ['en' => 'Puerto Colón', 'es' => 'Puerto Colón'],
            'slug' => 'puerto-colon',
            'is_active' => true,
        ]);

        // 3. Sectors / Categories
        $foodDrinks = \App\Models\Sector::create([
            'name' => ['en' => 'Food & Drinks', 'nl' => 'Eten & Drinken'],
            'slug' => 'food-drinks',
            'is_active' => true,
        ]);

        $restaurants = \App\Models\Category::create([
            'sector_id' => $foodDrinks->id,
            'name' => ['en' => 'Restaurants', 'nl' => 'Restaurants'],
            'slug' => 'restaurants',
            'is_active' => true,
        ]);

        $bars = \App\Models\Category::create([
            'sector_id' => $foodDrinks->id,
            'name' => ['en' => 'Bars', 'nl' => 'Bars'],
            'slug' => 'bars',
            'is_active' => true,
        ]);

        // 4. Category Specs
        $venueType = \App\Models\CategoryFilterSpec::create([
            'category_id' => $restaurants->id,
            'key' => 'venue_type',
            'name' => ['en' => 'Venue Type'],
            'type' => 'select',
            'is_filterable' => true,
            'is_active' => true,
        ]);

        $options = ['Guachinche', 'Bodega', 'Tapas', 'Asador', 'Fine dining', 'International', 'Seafood', 'Steakhouse'];
        foreach ($options as $opt) {
            \App\Models\CategorySpecOption::create([
                'spec_type' => \App\Models\CategoryFilterSpec::class,
                'spec_id' => $venueType->id,
                'value' => \Illuminate\Support\Str::slug($opt),
                'label' => ['en' => $opt],
                'is_active' => true,
            ]);
        }

        // 5. Example Users
        $users = [
            ['name' => 'Jan', 'email' => 'jan@example.com'],
            ['name' => 'Carlos', 'email' => 'carlos@example.com'],
            ['name' => 'Sofie', 'email' => 'sofie@example.com'],
        ];

        foreach ($users as $u) {
            \App\Models\User::factory()->create($u);
        }

        // 6. Example Spots
        \App\Models\Spot::factory()->create([
            'name' => ['en' => 'Example Restaurant'],
            'slug' => 'example-restaurant',
            'sector_id' => $foodDrinks->id,
            'category_id' => $restaurants->id,
            'region_id' => $tenerife->id,
            'area_id' => $costaAdeje->id,
            'place_id' => $puertoColon->id,
        ]);
    }
}
