<?php

namespace App\Filament\Admin\Resources\ClaimTokens\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ClaimTokenForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('spot_id')
                    ->relationship('spot', 'name')
                    ->required()
                    ->searchable(),
                Select::make('campaign_id')
                    ->relationship('campaign', 'name')
                    ->searchable(),
                TextInput::make('token')
                    ->default(fn () => Str::random(64))
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                DateTimePicker::make('expires_at')
                    ->default(now()->addDays(30))
                    ->required(),
                DateTimePicker::make('used_at')
                    ->disabled(),
            ]);
    }
}
