<?php

namespace App\Filament\Owner\Resources\MySpots\Pages;

use App\Filament\Owner\Resources\MySpots\MySpotResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMySpot extends EditRecord
{
    protected static string $resource = MySpotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
