<?php

namespace App\Filament\Admin\Resources\SpotVisits\Pages;

use App\Filament\Admin\Resources\SpotVisits\SpotVisitResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSpotVisits extends ListRecords
{
    protected static string $resource = SpotVisitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}



