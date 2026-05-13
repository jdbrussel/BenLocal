<?php

namespace App\Jobs\AI;

use App\Models\CampaignSubmission;
use App\Services\AI\AIEnrichmentService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class EnrichCampaignSpotJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected CampaignSubmission $submission) {}

    public function handle(AIEnrichmentService $enrichmentService): void
    {
        $enrichmentService->enrichCampaignSubmission($this->submission);
    }
}
