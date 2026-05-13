<?php

namespace App\Filament\Admin\Resources\SpotClaims\Pages;

use App\Filament\Admin\Resources\SpotClaims\SpotClaimResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSpotClaim extends EditRecord
{
    protected static string $resource = SpotClaimResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
