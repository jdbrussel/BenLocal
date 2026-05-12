<?php

namespace App\Filament\Admin\Resources\Sectors\Pages;

use App\Filament\Admin\Resources\Sectors\SectorResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSector extends EditRecord
{
    protected static string $resource = SectorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}



