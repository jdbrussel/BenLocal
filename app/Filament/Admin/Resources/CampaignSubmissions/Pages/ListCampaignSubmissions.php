<?php

namespace App\Filament\Admin\Resources\CampaignSubmissions\Pages;

use App\Filament\Admin\Resources\CampaignSubmissions\CampaignSubmissionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCampaignSubmissions extends ListRecords
{
    protected static string $resource = CampaignSubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}



