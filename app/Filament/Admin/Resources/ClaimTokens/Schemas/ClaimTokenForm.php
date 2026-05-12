<?php

namespace App\Filament\Admin\Resources\ClaimTokens\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ClaimTokenForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('spot_id')
                    ->required()
                    ->numeric(),
                TextInput::make('campaign_id')
                    ->numeric(),
                TextInput::make('token')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                DateTimePicker::make('expires_at'),
                DateTimePicker::make('used_at'),
            ]);
    }
}



