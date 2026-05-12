<?php

namespace App\Filament\Admin\Resources\CategorySpecOptions\Pages;

use App\Filament\Admin\Resources\CategorySpecOptions\CategorySpecOptionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCategorySpecOption extends EditRecord
{
    protected static string $resource = CategorySpecOptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}



