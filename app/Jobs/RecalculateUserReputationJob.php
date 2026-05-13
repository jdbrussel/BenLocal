<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Region;
use App\Services\UserReputationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RecalculateUserReputationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected User $user) {}

    public function handle(UserReputationService $service)
    {
        // Recalculate global reputation
        $service->recalculateReputation($this->user);

        // Recalculate reputation for each region they've interacted with
        $regions = Region::whereHas('recommendations', function($q) {
            $q->where('user_id', $this->user->id);
        })->get();

        foreach ($regions as $region) {
            $service->recalculateReputation($this->user, ['region_id' => $region->id]);
        }
    }
}
