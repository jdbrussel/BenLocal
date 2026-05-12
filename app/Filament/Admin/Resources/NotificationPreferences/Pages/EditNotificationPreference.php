<?php

namespace App\Filament\Admin\Resources\NotificationPreferences\Pages;

use App\Filament\Admin\Resources\NotificationPreferences\NotificationPreferenceResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditNotificationPreference extends EditRecord
{
    protected static string $resource = NotificationPreferenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}



