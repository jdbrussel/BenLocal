<?php

namespace App\Filament\Admin\Resources\UserRegionStatuses\Pages;

use App\Filament\Admin\Resources\UserRegionStatuses\UserRegionStatusResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUserRegionStatuses extends ListRecords
{
    protected static string $resource = UserRegionStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}



