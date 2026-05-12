<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

use App\Models\ContentReport;
use App\Models\Review;
use App\Models\SpotClaim;

class ModerationOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Open Reports', ContentReport::where('status', 'pending')->count()),
            Stat::make('Flagged Reviews', Review::where('moderation_status', 'flagged')->count()),
            Stat::make('Pending Claims', SpotClaim::where('status', 'pending')->count()),
        ];
    }
}
