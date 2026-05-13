<?php

namespace App\Jobs\AI;

use App\Models\Spot;
use App\Services\AI\AIEnrichmentService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class EnrichSpotJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected Spot $spot) {}

    public function handle(AIEnrichmentService $enrichmentService): void
    {
        $enrichmentService->enrichSpot($this->spot);
    }
}
