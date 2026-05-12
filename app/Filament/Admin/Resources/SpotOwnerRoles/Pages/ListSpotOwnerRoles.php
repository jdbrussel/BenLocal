<?php

namespace App\Filament\Admin\Resources\SpotOwnerRoles\Pages;

use App\Filament\Admin\Resources\SpotOwnerRoles\SpotOwnerRoleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSpotOwnerRoles extends ListRecords
{
    protected static string $resource = SpotOwnerRoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}



