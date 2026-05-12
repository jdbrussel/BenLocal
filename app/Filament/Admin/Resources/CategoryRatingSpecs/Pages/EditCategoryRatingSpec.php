<?php

namespace App\Filament\Admin\Resources\CategoryRatingSpecs\Pages;

use App\Filament\Admin\Resources\CategoryRatingSpecs\CategoryRatingSpecResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCategoryRatingSpec extends EditRecord
{
    protected static string $resource = CategoryRatingSpecResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}



