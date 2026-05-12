<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

use App\Models\Recommendation;

class HiddenGemCandidates extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Hidden Gem Candidates', Recommendation::where('hidden_gem_candidate', true)->count()),
        ];
    }
}
