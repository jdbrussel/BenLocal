<?php

namespace App\Filament\Admin\Resources\CampaignRecommendations\Pages;

use App\Filament\Admin\Resources\CampaignRecommendations\CampaignRecommendationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCampaignRecommendations extends ListRecords
{
    protected static string $resource = CampaignRecommendationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
