<?php

namespace Database\Seeders;

use App\Models\GdprExport;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class GdprExportSeeder extends Seeder
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

        // Pending export requests
        for ($i = 0; $i < 10; $i++) {
            GdprExport::create([
                'user_id' => $users->random()->id,
                'export_path' => null,
                'requested_at' => Carbon::now()->subDays(rand(1, 5)),
                'completed_at' => null,
            ]);
        }

        // Processing export requests (effectively pending but older)
        for ($i = 0; $i < 5; $i++) {
            GdprExport::create([
                'user_id' => $users->random()->id,
                'export_path' => null,
                'requested_at' => Carbon::now()->subDays(rand(6, 10)),
                'completed_at' => null,
            ]);
        }

        // Completed export requests
        for ($i = 0; $i < 15; $i++) {
            $user = $users->random();
            GdprExport::create([
                'user_id' => $user->id,
                'export_path' => "exports/user-{$user->id}-export-" . rand(100, 999) . ".zip",
                'requested_at' => Carbon::now()->subDays(rand(15, 30)),
                'completed_at' => Carbon::now()->subDays(rand(11, 14)),
            ]);
        }

        // Failed export requests (completed_at null, requested_at very old)
        for ($i = 0; $i < 5; $i++) {
            GdprExport::create([
                'user_id' => $users->random()->id,
                'export_path' => null,
                'requested_at' => Carbon::now()->subDays(rand(40, 50)),
                'completed_at' => null,
            ]);
        }
    }
}
