<?php

namespace App\Filament\Admin\Resources\GdprDeletions\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class GdprDeletionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                DateTimePicker::make('requested_at'),
                DateTimePicker::make('anonymized_at'),
                DateTimePicker::make('completed_at'),
            ]);
    }
}



