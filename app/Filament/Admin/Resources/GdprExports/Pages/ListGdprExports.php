<?php

namespace App\Filament\Admin\Resources\GdprExports\Pages;

use App\Filament\Admin\Resources\GdprExports\GdprExportResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGdprExports extends ListRecords
{
    protected static string $resource = GdprExportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}



