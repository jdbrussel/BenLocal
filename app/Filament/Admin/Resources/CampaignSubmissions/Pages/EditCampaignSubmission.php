<?php

namespace App\Filament\Admin\Resources\CampaignSubmissions\Pages;

use App\Filament\Admin\Resources\CampaignSubmissions\CampaignSubmissionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCampaignSubmission extends EditRecord
{
    protected static string $resource = CampaignSubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}



