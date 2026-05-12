<?php

namespace App\Filament\Admin\Resources\GdprExports\Pages;

use App\Filament\Admin\Resources\GdprExports\GdprExportResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGdprExport extends EditRecord
{
    protected static string $resource = GdprExportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}



