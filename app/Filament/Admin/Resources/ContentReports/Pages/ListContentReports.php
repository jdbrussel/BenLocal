<?php

namespace App\Filament\Admin\Resources\ContentReports\Pages;

use App\Filament\Admin\Resources\ContentReports\ContentReportResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListContentReports extends ListRecords
{
    protected static string $resource = ContentReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}



