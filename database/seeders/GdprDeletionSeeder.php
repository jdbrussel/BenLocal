<?php

namespace Database\Seeders;

use App\Models\GdprDeletion;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class GdprDeletionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            return;
        }

        // Pending deletion requests
        for ($i = 0; $i < 5; $i++) {
            GdprDeletion::create([
                'user_id' => $users->random()->id,
                'requested_at' => Carbon::now()->subDays(rand(1, 3)),
                'anonymized_at' => null,
                'completed_at' => null,
            ]);
        }

        // Processing deletion requests
        for ($i = 0; $i < 3; $i++) {
            GdprDeletion::create([
                'user_id' => $users->random()->id,
                'requested_at' => Carbon::now()->subDays(rand(4, 7)),
                'anonymized_at' => null,
                'completed_at' => null,
            ]);
        }

        // Completed deletion requests
        for ($i = 0; $i < 5; $i++) {
            // These would normally be associated with a user that is now anonymized
            // but the GdprDeletion record might still have the old user_id (if not constrained)
            // or be null if constrained with onDelete('set null').
            // The migration showed constrained() so it depends on if the user still exists.
            // If they are anonymized, they still exist but with different data.
            $user = $users->random();
            GdprDeletion::create([
                'user_id' => $user->id,
                'requested_at' => Carbon::now()->subDays(rand(20, 30)),
                'anonymized_at' => Carbon::now()->subDays(rand(10, 15)),
                'completed_at' => Carbon::now()->subDays(rand(10, 15)),
            ]);
        }

        // Anonymized users (requested and anonymized but maybe not "completed" in some sense)
        for ($i = 0; $i < 3; $i++) {
            $user = $users->random();
            GdprDeletion::create([
                'user_id' => $user->id,
                'requested_at' => Carbon::now()->subDays(rand(10, 15)),
                'anonymized_at' => Carbon::now()->subDays(rand(1, 5)),
                'completed_at' => null,
            ]);
        }
    }
}
