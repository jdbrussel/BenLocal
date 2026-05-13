<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RefreshCommunityProfilesCommand extends Command
{
    protected $signature = 'benlocal:refresh-community-profiles {--spot= : Specific spot ID}';
    protected $description = 'Refresh community distribution profiles for spots';

    public function handle(\App\Services\CommunityProfileService $profileService)
    {
        $spotId = $this->option('spot');

        if ($spotId) {
            $spot = \App\Models\Spot::findOrFail($spotId);
            $this->info("Refreshing community profile for: {$spot->name}");
            $profileService->updateSpotProfile($spot);
            return;
        }

        $this->info("Refreshing community profiles for all spots...");

        \App\Models\Spot::chunk(100, function ($spots) use ($profileService) {
            foreach ($spots as $spot) {
                $profileService->updateSpotProfile($spot);
                $this->output->write('.');
            }
        });

        $this->info("\nAll community profiles refreshed.");
    }
}
