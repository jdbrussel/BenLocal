<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DetectHiddenGemsCommand extends Command
{
    protected $signature = 'benlocal:detect-hidden-gems';
    protected $description = 'Run specialized pass to detect hidden gems';

    public function handle(\App\Services\HiddenGemService $hiddenGemService)
    {
        $this->info("Detecting hidden gems...");

        \App\Models\Spot::chunk(100, function ($spots) use ($hiddenGemService) {
            foreach ($spots as $spot) {
                $score = $hiddenGemService->calculateScore($spot);
                $spot->update(['hidden_gem_score' => $score]);

                // Logic to actually assign badges is usually in SpotRankingService,
                // but we can trigger it here or in a more centralized place.
                // For now, let's just update the score as requested by the command name.

                $this->output->write($score >= 70 ? 'G' : '.');
            }
        });

        $this->info("\nHidden gem detection complete.");
    }
}
