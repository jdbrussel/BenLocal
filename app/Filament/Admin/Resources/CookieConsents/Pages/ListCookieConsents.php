<?php

namespace App\Filament\Admin\Resources\CookieConsents\Pages;

use App\Filament\Admin\Resources\CookieConsents\CookieConsentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCookieConsents extends ListRecords
{
    protected static string $resource = CookieConsentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}



