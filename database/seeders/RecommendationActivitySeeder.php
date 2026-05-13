<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RecommendationActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $recommendations = \App\Models\Recommendation::with(['user', 'spot'])->get();
        $tenerife = \App\Models\Region::where('slug', 'tenerife')->first();

        foreach ($recommendations as $rec) {
            \App\Models\TimelineEvent::create([
                'user_id' => $rec->user_id,
                'type' => 'recommendation_created',
                'eventable_type' => \App\Models\Recommendation::class,
                'eventable_id' => $rec->id,
                'region_id' => $rec->region_id ?? $rec->spot->region_id ?? $tenerife->id,
                'payload' => [
                    'spot_id' => $rec->spot_id,
                    'spot_name' => $rec->spot->name,
                    'user_name' => $rec->user->name,
                    'community' => $rec->user->community?->name ?? 'Global',
                    'region' => $rec->spot->region?->name ?? 'Tenerife',
                    'hidden_gem_candidate' => $rec->is_hidden_gem_candidate,
                ],
                'created_at' => $rec->created_at,
            ]);
        }

        // Add campaign recommendations
        $campaigns = \App\Models\Campaign::all();
        if ($campaigns->isNotEmpty()) {
            for ($i = 0; $i < 50; $i++) {
                $rec = $recommendations->random();
                $campaign = $campaigns->random();

                \App\Models\TimelineEvent::create([
                    'user_id' => $rec->user_id,
                    'type' => 'campaign_recommendation_created',
                    'eventable_type' => \App\Models\Recommendation::class,
                    'eventable_id' => $rec->id,
                    'region_id' => $rec->region_id ?? $rec->spot->region_id ?? $tenerife->id,
                    'payload' => [
                        'campaign_id' => $campaign->id,
                        'campaign_name' => $campaign->name,
                        'spot_id' => $rec->spot_id,
                        'spot_name' => $rec->spot->name,
                        'user_name' => $rec->user->name,
                    ],
                    'created_at' => now()->subDays(rand(1, 15)),
                ]);
            }
        }
    }
}
