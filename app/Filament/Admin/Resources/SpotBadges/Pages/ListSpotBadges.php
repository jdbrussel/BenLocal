<?php

namespace App\Filament\Admin\Resources\SpotBadges\Pages;

use App\Filament\Admin\Resources\SpotBadges\SpotBadgeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSpotBadges extends ListRecords
{
    protected static string $resource = SpotBadgeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}



