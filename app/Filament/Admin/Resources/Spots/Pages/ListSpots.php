<?php

namespace App\Filament\Admin\Resources\Spots\Pages;

use App\Filament\Admin\Resources\Spots\SpotResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSpots extends ListRecords
{
    protected static string $resource = SpotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}



