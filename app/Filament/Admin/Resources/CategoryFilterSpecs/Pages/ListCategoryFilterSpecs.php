<?php

namespace App\Filament\Admin\Resources\CategoryFilterSpecs\Pages;

use App\Filament\Admin\Resources\CategoryFilterSpecs\CategoryFilterSpecResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCategoryFilterSpecs extends ListRecords
{
    protected static string $resource = CategoryFilterSpecResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}



