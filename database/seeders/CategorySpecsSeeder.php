<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\CategoryFilterSpec;
use App\Models\CategoryRatingSpec;
use App\Models\CategorySpecOption;
use Illuminate\Database\Seeder;

class CategorySpecsSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedRestaurantSpecs();
        $this->seedBarSpecs();
    }

    private function seedRestaurantSpecs(): void
    {
        $restaurant = Category::where('slug', 'restaurants')->first();

        // Rating Specs
        $ratingSpecs = [
            ['key' => 'food_quality', 'name' => ['nl' => 'Kwaliteit van het eten', 'en' => 'Food Quality', 'es' => 'Calidad de la comida']],
            ['key' => 'service', 'name' => ['nl' => 'Service', 'en' => 'Service', 'es' => 'Servicio']],
            ['key' => 'atmosphere', 'name' => ['nl' => 'Sfeer', 'en' => 'Atmosphere', 'es' => 'Ambiente']],
            ['key' => 'value_for_money', 'name' => ['nl' => 'Prijs-kwaliteit', 'en' => 'Value for Money', 'es' => 'Relación calidad-precio']],
        ];

        foreach ($ratingSpecs as $index => $spec) {
            CategoryRatingSpec::updateOrCreate(
                ['category_id' => $restaurant->id, 'key' => $spec['key']],
                [
                    'name' => $spec['name'],
                    'type' => 'star_rating',
                    'min_value' => 1,
                    'max_value' => 5,
                    'sort_order' => $index,
                ]
            );
        }

        // Filter Specs
        $this->seedFilterSpec($restaurant->id, 'venue_type', ['nl' => 'Type zaak', 'en' => 'Venue Type'], 'multiselect', [
            'guachinche' => ['nl' => 'Guachinche', 'en' => 'Guachinche'],
            'bodega' => ['nl' => 'Bodega', 'en' => 'Bodega'],
            'tapas' => ['nl' => 'Tapas', 'en' => 'Tapas'],
            'asador' => ['nl' => 'Asador', 'en' => 'Asador'],
            'fine_dining' => ['nl' => 'Fine Dining', 'en' => 'Fine Dining'],
            'international' => ['nl' => 'Internationaal', 'en' => 'International'],
            'seafood' => ['nl' => 'Zeevruchten', 'en' => 'Seafood'],
            'steakhouse' => ['nl' => 'Steakhouse', 'en' => 'Steakhouse'],
        ]);

        $this->seedFilterSpec($restaurant->id, 'cuisine', ['nl' => 'Keuken', 'en' => 'Cuisine'], 'multiselect', [
            'canarian' => ['nl' => 'Canarisch', 'en' => 'Canarian'],
            'spanish' => ['nl' => 'Spaans', 'en' => 'Spanish'],
            'italian' => ['nl' => 'Italiaans', 'en' => 'Italian'],
            'asian' => ['nl' => 'Aziatisch', 'en' => 'Asian'],
            'international' => ['nl' => 'Internationaal', 'en' => 'International'],
            'belgian' => ['nl' => 'Belgisch', 'en' => 'Belgian'],
            'dutch' => ['nl' => 'Nederlands', 'en' => 'Dutch'],
            'seafood' => ['nl' => 'Zeevruchten', 'en' => 'Seafood'],
            'grill' => ['nl' => 'Grill', 'en' => 'Grill'],
        ]);

        $this->seedFilterSpec($restaurant->id, 'price_class', ['nl' => 'Prijsklasse', 'en' => 'Price Class'], 'select', [
            '€' => ['nl' => '€', 'en' => '€'],
            '€€' => ['nl' => '€€', 'en' => '€€'],
            '€€€' => ['nl' => '€€€', 'en' => '€€€'],
            '€€€€' => ['nl' => '€€€€', 'en' => '€€€€'],
        ]);

        $booleanFilters = [
            ['key' => 'terrace', 'name' => ['nl' => 'Terras', 'en' => 'Terrace']],
            ['key' => 'child_friendly', 'name' => ['nl' => 'Kindvriendelijk', 'en' => 'Child Friendly']],
            ['key' => 'vegetarian_options', 'name' => ['nl' => 'Vegetarische opties', 'en' => 'Vegetarian Options']],
            ['key' => 'sea_view', 'name' => ['nl' => 'Uitzicht op zee', 'en' => 'Sea View']],
        ];

        foreach ($booleanFilters as $spec) {
            CategoryFilterSpec::updateOrCreate(
                ['category_id' => $restaurant->id, 'key' => $spec['key']],
                ['name' => $spec['name'], 'type' => 'boolean']
            );
        }
    }

    private function seedBarSpecs(): void
    {
        $bar = Category::where('slug', 'bars')->first();

        // Rating Specs
        $ratingSpecs = [
            ['key' => 'ambiance', 'name' => ['nl' => 'Sfeer', 'en' => 'Ambiance']],
            ['key' => 'drink_selection', 'name' => ['nl' => 'Drankselectie', 'en' => 'Drink Selection']],
            ['key' => 'music', 'name' => ['nl' => 'Muziek', 'en' => 'Music']],
            ['key' => 'service', 'name' => ['nl' => 'Service', 'en' => 'Service']],
            ['key' => 'price_level', 'name' => ['nl' => 'Prijsniveau', 'en' => 'Price Level']],
        ];

        foreach ($ratingSpecs as $index => $spec) {
            CategoryRatingSpec::updateOrCreate(
                ['category_id' => $bar->id, 'key' => $spec['key']],
                [
                    'name' => $spec['name'],
                    'type' => 'star_rating',
                    'min_value' => 1,
                    'max_value' => 5,
                    'sort_order' => $index,
                ]
            );
        }

        // Filter Specs
        $this->seedFilterSpec($bar->id, 'venue_type', ['nl' => 'Type zaak', 'en' => 'Venue Type'], 'multiselect', [
            'beachbar' => ['nl' => 'Beachbar', 'en' => 'Beach Bar'],
            'cocktailbar' => ['nl' => 'Cocktailbar', 'en' => 'Cocktail Bar'],
            'pub' => ['nl' => 'Pub', 'en' => 'Pub'],
            'bierbar' => ['nl' => 'Bierbar', 'en' => 'Beer Bar'],
            'wijnbar' => ['nl' => 'Wijnbar', 'en' => 'Wine Bar'],
            'loungebar' => ['nl' => 'Loungebar', 'en' => 'Lounge Bar'],
            'eetcafe' => ['nl' => 'Eetcafé', 'en' => 'Eatery/Pub'],
        ]);

        $this->seedFilterSpec($bar->id, 'drink_focus', ['nl' => 'Drankfocus', 'en' => 'Drink Focus'], 'multiselect', [
            'cocktails' => ['nl' => 'Cocktails', 'en' => 'Cocktails'],
            'beer' => ['nl' => 'Bier', 'en' => 'Beer'],
            'wine' => ['nl' => 'Wijn', 'en' => 'Wine'],
            'gin_tonic' => ['nl' => 'Gin Tonic', 'en' => 'Gin & Tonic'],
            'tapas' => ['nl' => 'Tapas', 'en' => 'Tapas'],
            'coffee' => ['nl' => 'Koffie', 'en' => 'Coffee'],
        ]);

        $booleanFilters = [
            ['key' => 'live_music', 'name' => ['nl' => 'Live muziek', 'en' => 'Live Music']],
            ['key' => 'terrace', 'name' => ['nl' => 'Terras', 'en' => 'Terrace']],
            ['key' => 'sea_view', 'name' => ['nl' => 'Uitzicht op zee', 'en' => 'Sea View']],
            ['key' => 'open_late', 'name' => ['nl' => 'Laat open', 'en' => 'Open Late']],
        ];

        foreach ($booleanFilters as $spec) {
            CategoryFilterSpec::updateOrCreate(
                ['category_id' => $bar->id, 'key' => $spec['key']],
                ['name' => $spec['name'], 'type' => 'boolean']
            );
        }
    }

    private function seedFilterSpec($categoryId, $key, $name, $type, $options): void
    {
        $spec = CategoryFilterSpec::updateOrCreate(
            ['category_id' => $categoryId, 'key' => $key],
            ['name' => $name, 'type' => $type]
        );

        foreach ($options as $value => $label) {
            CategorySpecOption::updateOrCreate(
                [
                    'spec_type' => CategoryFilterSpec::class,
                    'spec_id' => $spec->id,
                    'value' => $value,
                ],
                ['label' => $label]
            );
        }
    }
}
