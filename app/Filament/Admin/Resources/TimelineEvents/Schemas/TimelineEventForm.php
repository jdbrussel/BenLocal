<?php

namespace App\Filament\Admin\Resources\TimelineEvents\Schemas;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TimelineEventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable(),
                TextInput::make('type')
                    ->required(),
                TextInput::make('eventable_type')
                    ->disabled(),
                TextInput::make('eventable_id')
                    ->numeric()
                    ->disabled(),
                Select::make('region_id')
                    ->relationship('region', 'name')
                    ->searchable(),
                KeyValue::make('payload')
                    ->columnSpanFull()
                    ->label('Event Data (Debug)'),
            ]);
    }
}
