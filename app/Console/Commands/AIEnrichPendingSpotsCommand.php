<?php

namespace App\Console\Commands;

use App\Jobs\AI\EnrichCampaignSpotJob;
use App\Jobs\AI\EnrichSpotJob;
use App\Models\CampaignSubmission;
use App\Models\Spot;
use Illuminate\Console\Command;

class AIEnrichPendingSpotsCommand extends Command
{
    protected $signature = 'benlocal:enrich-pending-spots';
    protected $description = 'Enrich pending spots and campaign submissions with AI data';

    public function handle()
    {
        $this->info("Starting enrichment for pending spots...");

        // Enrichment for Spots that are not yet enriched
        $spots = Spot::where('ai_enriched', false)
            ->whereNull('verified_at')
            ->get();

        foreach ($spots as $spot) {
            EnrichSpotJob::dispatch($spot);
        }

        $this->info("Dispatched " . $spots->count() . " spot enrichment jobs.");

        $this->info("Starting enrichment for pending campaign submissions...");

        // Enrichment for campaign submissions that are pending
        $submissions = CampaignSubmission::where('status', 'pending')
            ->get();

        foreach ($submissions as $submission) {
            EnrichCampaignSpotJob::dispatch($submission);
        }

        $this->info("Dispatched " . $submissions->count() . " submission enrichment jobs.");
    }
}
