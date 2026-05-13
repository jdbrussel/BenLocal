<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RecalculateReputationCommand extends Command
{
    protected $signature = 'benlocal:recalculate-reputation {--user= : Specific user ID}';
    protected $description = 'Recalculate user reputation scores';

    public function handle(\App\Services\UserReputationService $reputationService)
    {
        $userId = $this->option('user');

        if ($userId) {
            $user = \App\Models\User::findOrFail($userId);
            $this->info("Recalculating reputation for: {$user->name}");
            // We should ideally calculate for all relevant regions/contexts
            // For now, let's assume we recalculate for their primary regions or just global
            $reputationService->recalculateReputation($user);
            return;
        }

        $this->info("Recalculating reputation for all users...");

        \App\Models\User::chunk(100, function ($users) use ($reputationService) {
            foreach ($users as $user) {
                $reputationService->recalculateReputation($user);
                $this->output->write('.');
            }
        });

        $this->info("\nAll user reputations recalculated.");
    }
}
