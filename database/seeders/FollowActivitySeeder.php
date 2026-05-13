<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FollowActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $follows = \App\Models\Follow::with(['follower', 'followed'])->get();

        foreach ($follows as $follow) {
            \App\Models\TimelineEvent::create([
                'user_id' => $follow->follower_id,
                'type' => 'user_followed',
                'eventable_type' => \App\Models\Follow::class,
                'eventable_id' => $follow->id,
                'payload' => [
                    'follower_id' => $follow->follower_id,
                    'followed_id' => $follow->followed_id,
                    'follower_name' => $follow->follower->name,
                    'followed_name' => $follow->followed->name,
                ],
                'created_at' => now()->subDays(rand(1, 30)),
            ]);
        }

        // Add more random follows to reach 100+ events
        $users = \App\Models\User::all();
        $count = 100 - $follows->count();

        for ($i = 0; $i < max(0, $count); $i++) {
            $follower = $users->random();
            $followed = $users->where('id', '!=', $follower->id)->random();

            $follow = \App\Models\Follow::firstOrCreate([
                'follower_id' => $follower->id,
                'followed_id' => $followed->id,
            ]);

            \App\Models\TimelineEvent::create([
                'user_id' => $follower->id,
                'type' => 'user_followed',
                'eventable_type' => \App\Models\Follow::class,
                'eventable_id' => $follow->id,
                'payload' => [
                    'follower_id' => $follower->id,
                    'followed_id' => $followed->id,
                    'follower_name' => $follower->name,
                    'followed_name' => $followed->name,
                ],
                'created_at' => now()->subDays(rand(1, 30)),
            ]);
        }
    }
}
