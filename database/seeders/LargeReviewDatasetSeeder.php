<?php

namespace Database\Seeders;

use App\Models\Spot;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LargeReviewDatasetSeeder extends Seeder
{
    public function run(int $count = 20000): void
    {
        $this->command->info("Seeding {$count} reviews with realistic distribution...");

        $userIds = User::pluck('id')->toArray();
        $spots = Spot::select('id', 'name')->get();

        if (empty($userIds) || $spots->isEmpty()) {
            $this->command->error('Users or Spots missing.');
            return;
        }

        $batchSize = 500;
        $totalCreated = 0;

        // Find the popular spot created in LargeSpotDatasetSeeder
        $popularSpot = $spots->first(fn($s) => ($s->name['en'] ?? '') === 'The Legendary Popular Place');

        while ($totalCreated < $count) {
            $reviews = [];
            $currentBatch = min($batchSize, $count - $totalCreated);

            for ($i = 0; $i < $currentBatch; $i++) {
                $spotId = $this->getWeightedSpotId($spots, $popularSpot?->id);
                $lang = collect(['en', 'es', 'nl', 'de', 'fr'])->random();

                $reviews[] = [
                    'user_id' => $userIds[array_rand($userIds)],
                    'spot_id' => $spotId,
                    'overall_rating' => $this->getRandomRating(),
                    'rating_values' => json_encode([
                        'food' => rand(1, 5),
                        'service' => rand(1, 5),
                        'ambience' => rand(1, 5),
                        'value' => rand(1, 5),
                    ]),
                    'review_text' => json_encode([$lang => "This is a benchmark review text for performance testing. It contains enough characters to simulate realistic data."]),
                    'original_language' => $lang,
                    'visited_at' => now()->subDays(rand(0, 180)),
                    'confirms_recommendation' => (bool)rand(0, 1),
                    'visibility_score' => mt_rand(0, 100) / 100,
                    'moderation_status' => 'approved',
                    'verified_visit' => (bool)rand(0, 1),
                    'created_at' => now()->subDays(rand(0, 180)),
                    'updated_at' => now(),
                ];
            }

            DB::table('reviews')->insert($reviews);
            $totalCreated += $currentBatch;
            $this->command->comment("Seeded {$totalCreated}/{$count} reviews...");
        }
    }

    private function getWeightedSpotId($spots, $popularSpotId): int
    {
        // 10% chance to pick the popular spot
        if ($popularSpotId && mt_rand(1, 100) <= 10) {
            return $popularSpotId;
        }

        return $spots->random()->id;
    }

    private function getRandomRating(): float
    {
        // Bias towards positive reviews
        $weights = [1 => 5, 2 => 5, 3 => 15, 4 => 40, 5 => 35];
        $r = rand(1, 100);
        $sum = 0;
        foreach ($weights as $rating => $weight) {
            $sum += $weight;
            if ($r <= $sum) return (float)$rating;
        }
        return 4.0;
    }
}
