<?php

namespace App\Filament\Admin\Resources\GdprDeletions\Pages;

use App\Filament\Admin\Resources\GdprDeletions\GdprDeletionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGdprDeletion extends EditRecord
{
    protected static string $resource = GdprDeletionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}



