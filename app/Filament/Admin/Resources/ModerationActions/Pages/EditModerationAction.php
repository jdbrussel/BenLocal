<?php

namespace App\Filament\Admin\Resources\ModerationActions\Pages;

use App\Filament\Admin\Resources\ModerationActions\ModerationActionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditModerationAction extends EditRecord
{
    protected static string $resource = ModerationActionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}



