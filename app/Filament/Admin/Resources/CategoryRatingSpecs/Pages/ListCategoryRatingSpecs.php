<?php

namespace App\Filament\Admin\Resources\CategoryRatingSpecs\Pages;

use App\Filament\Admin\Resources\CategoryRatingSpecs\CategoryRatingSpecResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCategoryRatingSpecs extends ListRecords
{
    protected static string $resource = CategoryRatingSpecResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}



