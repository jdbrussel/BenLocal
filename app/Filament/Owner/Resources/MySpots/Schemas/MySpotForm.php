<?php

namespace App\Filament\Owner\Resources\MySpots\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MySpotForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Information')
                    ->schema([
                        TextInput::make('name')
                            ->disabled() // Owners cannot change name
                            ->required(),
                        TextInput::make('email')
                            ->email()
                            ->maxLength(255),
                        TextInput::make('phone')
                            ->tel()
                            ->maxLength(255),
                        TextInput::make('website')
                            ->url()
                            ->maxLength(255),
                    ])->columns(2),

                Section::make('Opening Hours')
                    ->schema([
                        KeyValue::make('opening_hours')
                            ->keyLabel('Day')
                            ->valueLabel('Hours')
                            ->columnSpanFull(),
                    ]),

                Section::make('Photos')
                    ->schema([
                        FileUpload::make('photos')
                            ->multiple()
                            ->directory('spot-photos')
                            ->image()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
