<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearStaleCacheCommand extends Command
{
    protected $signature = 'benlocal:clear-stale-cache';
    protected $description = 'Clear stale cache entries for rankings and discovery';

    public function handle()
    {
        $this->info("Clearing stale cache...");

        // Specific tags for rankings/discovery if we use tagged cache
        if (\Illuminate\Support\Facades\Cache::supportsTags()) {
            \Illuminate\Support\Facades\Cache::tags(['rankings', 'discovery', 'spots'])->flush();
            $this->info("Flushed tagged cache: rankings, discovery, spots.");
        } else {
            // Fallback: This might be dangerous if we clear EVERYTHING
            // but for production readiness we should ideally use tagged cache.
            $this->warn("Cache driver does not support tags. No specific tags cleared.");
        }

        $this->info("Cache maintenance complete.");
    }
}
