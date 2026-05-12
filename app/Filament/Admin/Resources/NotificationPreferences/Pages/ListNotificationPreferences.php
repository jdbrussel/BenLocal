<?php

namespace App\Filament\Admin\Resources\NotificationPreferences\Pages;

use App\Filament\Admin\Resources\NotificationPreferences\NotificationPreferenceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListNotificationPreferences extends ListRecords
{
    protected static string $resource = NotificationPreferenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}



