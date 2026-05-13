<?php

namespace App\Filament\Admin\Resources\CampaignRecommendations\Pages;

use App\Filament\Admin\Resources\CampaignRecommendations\CampaignRecommendationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCampaignRecommendation extends EditRecord
{
    protected static string $resource = CampaignRecommendationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
