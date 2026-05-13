<?php

namespace App\Filament\Admin\Resources\NotificationLogs\Pages;

use App\Filament\Admin\Resources\NotificationLogs\NotificationLogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageNotificationLogs extends ManageRecords
{
    protected static string $resource = NotificationLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
