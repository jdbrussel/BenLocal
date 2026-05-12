<?php

namespace App\Filament\Admin\Resources\UserRegionStatuses\Pages;

use App\Filament\Admin\Resources\UserRegionStatuses\UserRegionStatusResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditUserRegionStatus extends EditRecord
{
    protected static string $resource = UserRegionStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}



