<?php

namespace App\Filament\Admin\Resources\GdprExports\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class GdprExportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('export_path'),
                DateTimePicker::make('requested_at'),
                DateTimePicker::make('completed_at'),
            ]);
    }
}



