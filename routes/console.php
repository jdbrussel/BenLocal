<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use App\Models\User;
use App\Models\Spot;
use App\Jobs\RecalculateUserReputationJob;
use App\Jobs\RecalculateSpotScoresJob;
use Illuminate\Support\Facades\Schedule;

Schedule::command('benlocal:recalculate-reputation')->dailyAt('01:00');
Schedule::command('benlocal:recalculate-rankings')->dailyAt('02:00');
Schedule::command('benlocal:refresh-community-profiles')->dailyAt('03:00');
Schedule::command('benlocal:detect-hidden-gems')->dailyAt('04:00');
Schedule::command('benlocal:clear-stale-cache')->dailyAt('05:00');
