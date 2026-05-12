<?php

namespace App\Filament\Admin\Resources\CategorySpecOptions\Pages;

use App\Filament\Admin\Resources\CategorySpecOptions\CategorySpecOptionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCategorySpecOptions extends ListRecords
{
    protected static string $resource = CategorySpecOptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}



