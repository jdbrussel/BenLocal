<?php

namespace Database\Seeders;

use App\Models\Spot;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QueueJobDemoSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Preparing records for queue job demonstration...');

        // 1. Spots needing ranking recalculation
        // We'll set updated_at to a while ago for some spots
        Spot::query()->limit(100)->update(['updated_at' => now()->subDays(2)]);
        $this->command->info('Marked 100 spots for ranking recalculation.');

        // 2. Users needing reputation recalculation
        User::query()->limit(50)->update(['updated_at' => now()->subDays(2)]);
        $this->command->info('Marked 50 users for reputation recalculation.');

        // 3. Spots needing community profile refresh
        // (Handled by benlocal:refresh-community-profiles which usually scans all spots)

        if (env('BENLOCAL_DISPATCH_BENCHMARK_JOBS', false)) {
            $this->command->info('Dispatching benchmark jobs to queue...');
            // In a real scenario, we might call artisan commands or dispatch jobs here
            // Artisan::call('benlocal:recalculate-rankings');
        } else {
            $this->command->warn('Skipping job dispatch as BENLOCAL_DISPATCH_BENCHMARK_JOBS is false.');
        }
    }
}
