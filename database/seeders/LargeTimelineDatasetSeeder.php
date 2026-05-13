<?php

namespace Database\Seeders;

use App\Models\Region;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LargeTimelineDatasetSeeder extends Seeder
{
    public function run(int $count = 100000): void
    {
        $this->command->info("Seeding {$count} timeline events...");

        $userIds = User::pluck('id')->toArray();
        $regionId = Region::where('slug', 'tenerife')->first()?->id;

        if (empty($userIds)) {
            $this->command->error('Users missing.');
            return;
        }

        $batchSize = 2000;
        $totalCreated = 0;

        $types = [
            'recommendation_created',
            'review_created',
            'review_reaction_created',
            'user_followed',
            'hidden_gem_detected',
            'spot_status_changed',
            'campaign_submission_created',
            'spot_saved',
        ];

        while ($totalCreated < $count) {
            $events = [];
            $currentBatch = min($batchSize, $count - $totalCreated);

            for ($i = 0; $i < $currentBatch; $i++) {
                $events[] = [
                    'user_id' => $userIds[array_rand($userIds)],
                    'type' => $types[array_rand($types)],
                    'eventable_type' => null, // Simplified for benchmark
                    'eventable_id' => null,
                    'payload' => json_encode(['benchmark' => true]),
                    'region_id' => $regionId,
                    'created_at' => now()->subMinutes(rand(0, 259200)), // Last 6 months
                    'updated_at' => now(),
                ];
            }

            DB::table('timeline_events')->insert($events);
            $totalCreated += $currentBatch;
            $this->command->comment("Seeded {$totalCreated}/{$count} timeline events...");
        }
    }
}
