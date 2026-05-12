<?php

namespace App\Filament\Admin\Resources\SpotVisits\Pages;

use App\Filament\Admin\Resources\SpotVisits\SpotVisitResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSpotVisit extends EditRecord
{
    protected static string $resource = SpotVisitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}



