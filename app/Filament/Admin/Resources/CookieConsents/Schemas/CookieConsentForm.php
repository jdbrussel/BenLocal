<?php

namespace App\Filament\Admin\Resources\CookieConsents\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CookieConsentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->numeric(),
                TextInput::make('session_id'),
                Toggle::make('necessary')
                    ->required(),
                Toggle::make('analytics')
                    ->required(),
                Toggle::make('personalization')
                    ->required(),
                Toggle::make('marketing')
                    ->required(),
                TextInput::make('ip_address'),
                TextInput::make('user_agent'),
                DateTimePicker::make('consented_at'),
            ]);
    }
}



