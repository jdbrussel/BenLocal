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

Schedule::call(function () {
    User::chunk(100, function ($users) {
        foreach ($users as $user) {
            RecalculateUserReputationJob::dispatch($user);
        }
    });
})->daily();

Schedule::call(function () {
    Spot::chunk(100, function ($spots) {
        foreach ($spots as $spot) {
            RecalculateSpotScoresJob::dispatch($spot);
        }
    });
})->dailyAt('02:00');
