<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

use App\Models\User;
use App\Models\Spot;
use App\Models\Recommendation;
use App\Models\Review;

class PlatformOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count()),
            Stat::make('Total Spots', Spot::count()),
            Stat::make('Total Recommendations', Recommendation::count()),
            Stat::make('Total Reviews', Review::count()),
        ];
    }
}
