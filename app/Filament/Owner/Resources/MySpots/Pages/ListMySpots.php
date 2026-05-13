<?php

namespace App\Filament\Owner\Resources\MySpots\Pages;

use App\Filament\Owner\Resources\MySpots\MySpotResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMySpots extends ListRecords
{
    protected static string $resource = MySpotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
