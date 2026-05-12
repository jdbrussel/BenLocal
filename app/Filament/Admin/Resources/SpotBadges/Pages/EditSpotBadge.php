<?php

namespace App\Filament\Admin\Resources\SpotBadges\Pages;

use App\Filament\Admin\Resources\SpotBadges\SpotBadgeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSpotBadge extends EditRecord
{
    protected static string $resource = SpotBadgeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}



