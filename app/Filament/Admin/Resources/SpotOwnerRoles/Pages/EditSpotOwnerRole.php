<?php

namespace App\Filament\Admin\Resources\SpotOwnerRoles\Pages;

use App\Filament\Admin\Resources\SpotOwnerRoles\SpotOwnerRoleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSpotOwnerRole extends EditRecord
{
    protected static string $resource = SpotOwnerRoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}



