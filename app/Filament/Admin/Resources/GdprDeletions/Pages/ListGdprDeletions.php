<?php

namespace App\Filament\Admin\Resources\GdprDeletions\Pages;

use App\Filament\Admin\Resources\GdprDeletions\GdprDeletionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGdprDeletions extends ListRecords
{
    protected static string $resource = GdprDeletionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}



