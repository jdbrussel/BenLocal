<?php

namespace App\Filament\Admin\Resources\CookieConsents\Pages;

use App\Filament\Admin\Resources\CookieConsents\CookieConsentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCookieConsent extends EditRecord
{
    protected static string $resource = CookieConsentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}



