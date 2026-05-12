<?php

namespace App\Filament\Admin\Resources\ContentReports\Pages;

use App\Filament\Admin\Resources\ContentReports\ContentReportResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditContentReport extends EditRecord
{
    protected static string $resource = ContentReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}



