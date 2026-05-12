<?php

namespace App\Filament\Admin\Resources\SpotVisits\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SpotVisitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('spot_id')
                    ->required()
                    ->numeric(),
                DateTimePicker::make('checked_in_at')
                    ->required(),
                TextInput::make('visit_source')
                    ->required(),
                TextInput::make('latitude')
                    ->numeric(),
                TextInput::make('longitude')
                    ->numeric(),
                TextInput::make('verification_score')
                    ->numeric(),
            ]);
    }
}



