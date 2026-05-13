<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Category;
use App\Models\Region;
use App\Models\Sector;
use App\Models\Spot;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LargeSpotDatasetSeeder extends Seeder
{
    public function run(int $count = 1000): void
    {
        $this->command->info("Seeding {$count} spots across Tenerife clusters...");

        $region = Region::where('slug', 'tenerife')->first();
        if (!$region) return;

        $areas = Area::where('region_id', $region->id)->get();
        $categories = Category::all();
        $sectors = Sector::all();
        $users = User::pluck('id')->toArray();

        if ($categories->isEmpty() || $sectors->isEmpty() || empty($users)) {
            $this->command->error('Categories, Sectors or Users missing. Run base seeders first.');
            return;
        }

        $clusters = [
            'costa-adeje' => ['lat' => 28.077, 'lng' => -16.732, 'radius' => 0.015, 'weight' => 0.3],
            'playa-de-las-americas' => ['lat' => 28.059, 'lng' => -16.729, 'radius' => 0.01, 'weight' => 0.25],
            'los-cristianos' => ['lat' => 28.052, 'lng' => -16.716, 'radius' => 0.012, 'weight' => 0.2],
            'puerto-de-la-cruz' => ['lat' => 28.412, 'lng' => -16.544, 'radius' => 0.015, 'weight' => 0.1],
            'el-medano' => ['lat' => 28.045, 'lng' => -16.536, 'radius' => 0.01, 'weight' => 0.08],
            'la-laguna' => ['lat' => 28.487, 'lng' => -16.315, 'radius' => 0.02, 'weight' => 0.05],
            'icod-de-los-vinos' => ['lat' => 28.367, 'lng' => -16.711, 'radius' => 0.02, 'weight' => 0.02],
        ];

        $batchSize = 100;
        $totalCreated = 0;

        while ($totalCreated < $count) {
            $spots = [];
            $currentBatch = min($batchSize, $count - $totalCreated);

            for ($i = 0; $i < $currentBatch; $i++) {
                $clusterKey = $this->weightedRandom(array_map(fn($c) => $c['weight'], $clusters));
                $cluster = $clusters[$clusterKey];

                $area = $areas->where('slug', $clusterKey)->first() ?? $areas->random();
                $sector = $sectors->random();
                $category = $categories->where('sector_id', $sector->id)->random() ?? $categories->random();

                $lat = $cluster['lat'] + (mt_rand(-1000, 1000) / 100000) * ($cluster['radius'] * 100);
                $lng = $cluster['lng'] + (mt_rand(-1000, 1000) / 100000) * ($cluster['radius'] * 100);

                $name = "Benchmark Spot " . ($totalCreated + $i + 1);
                $lang = collect(['en', 'es', 'nl'])->random();

                $spots[] = [
                    'name' => json_encode([$lang => $name]),
                    'slug' => \Illuminate\Support\Str::slug($name) . '-' . \Illuminate\Support\Str::random(5),
                    'description' => json_encode([$lang => "This is a benchmark spot for performance testing."]),
                    'original_language' => $lang,
                    'sector_id' => $sector->id,
                    'category_id' => $category->id,
                    'region_id' => $region->id,
                    'area_id' => $area->id,
                    'latitude' => $lat,
                    'longitude' => $lng,
                    'address' => json_encode([
                        'street' => 'Benchmark St ' . rand(1, 999),
                        'city' => $area->name['en'] ?? 'Unknown',
                        'postal_code' => '3800' . rand(0, 9),
                    ]),
                    'price_level' => rand(1, 4),
                    'spec_values' => json_encode([
                        'venue_type' => collect(['restaurant', 'bar', 'cafe'])->random(),
                        'cuisine' => collect(['canarian', 'spanish', 'international'])->random(),
                        'price_class' => collect(['€', '€€', '€€€'])->random(),
                        'terrace' => (bool)rand(0, 1),
                        'sea_view' => (bool)rand(0, 1),
                    ]),
                    'lifecycle_status' => 'active',
                    'is_claimed' => false,
                    'created_by' => $users[array_rand($users)],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            DB::table('spots')->insert($spots);
            $totalCreated += $currentBatch;
            $this->command->comment("Seeded {$totalCreated}/{$count} spots...");
        }

        // Create one "Very Popular Spot" for edge case testing
        $popularSpot = Spot::factory()->create([
            'name' => ['en' => 'The Legendary Popular Place'],
            'area_id' => $areas->where('slug', 'costa-adeje')->first()?->id,
            'latitude' => 28.077,
            'longitude' => -16.732,
        ]);

        // Create one "Hidden Gem Candidate"
        Spot::factory()->create([
            'name' => ['en' => 'Authentic Secret Guachinche'],
            'area_id' => $areas->where('slug', 'icod-de-los-vinos')->first()?->id,
            'latitude' => 28.36,
            'longitude' => -16.71,
        ]);
    }

    private function weightedRandom(array $weights): string
    {
        $r = mt_rand() / mt_getrandmax();
        $sum = 0;
        foreach ($weights as $key => $weight) {
            $sum += $weight;
            if ($r <= $sum) return $key;
        }
        return array_key_first($weights);
    }
}
