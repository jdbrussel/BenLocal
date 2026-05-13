<?php

namespace App\Filament\Admin\Resources\SpotClaims\Pages;

use App\Filament\Admin\Resources\SpotClaims\SpotClaimResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSpotClaims extends ListRecords
{
    protected static string $resource = SpotClaimResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
