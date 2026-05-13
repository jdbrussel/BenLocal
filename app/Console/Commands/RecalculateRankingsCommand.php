<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RecalculateRankingsCommand extends Command
{
    protected $signature = 'benlocal:recalculate-rankings {--spot= : Specific spot ID}';
    protected $description = 'Recalculate all spot scores and rankings';

    public function handle(\App\Services\SpotRankingService $rankingService)
    {
        $spotId = $this->option('spot');

        if ($spotId) {
            $spot = \App\Models\Spot::findOrFail($spotId);
            $this->info("Recalculating scores for: {$spot->name}");
            $rankingService->recalculateScores($spot);
            return;
        }

        $this->info("Recalculating scores for all spots...");

        \App\Models\Spot::chunk(100, function ($spots) use ($rankingService) {
            foreach ($spots as $spot) {
                $rankingService->recalculateScores($spot);
                $this->output->write('.');
            }
        });

        $this->info("\nAll spot rankings recalculated.");
    }
}
