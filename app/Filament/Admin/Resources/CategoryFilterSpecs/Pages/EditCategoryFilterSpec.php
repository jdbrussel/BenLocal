<?php

namespace App\Filament\Admin\Resources\CategoryFilterSpecs\Pages;

use App\Filament\Admin\Resources\CategoryFilterSpecs\CategoryFilterSpecResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCategoryFilterSpec extends EditRecord
{
    protected static string $resource = CategoryFilterSpecResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}



