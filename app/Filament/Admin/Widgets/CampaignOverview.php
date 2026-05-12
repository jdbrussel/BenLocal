<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

use App\Models\Campaign;
use App\Models\CampaignSubmission;
use App\Models\CampaignRecommendation;

class CampaignOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Active Campaigns', Campaign::where('is_active', true)->count()),
            Stat::make('New Submissions', CampaignSubmission::where('status', 'pending')->count()),
            Stat::make('Selected for Publication', CampaignRecommendation::where('selected_for_publication', true)->count()),
        ];
    }
}
