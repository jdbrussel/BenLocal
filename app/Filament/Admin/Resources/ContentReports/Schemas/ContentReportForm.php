<?php

namespace App\Filament\Admin\Resources\ContentReports\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ContentReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('reporter_user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('reportable_type')
                    ->required(),
                TextInput::make('reportable_id')
                    ->required()
                    ->numeric(),
                TextInput::make('reason')
                    ->required(),
                Textarea::make('notes')
                    ->columnSpanFull(),
                TextInput::make('status')
                    ->required(),
                TextInput::make('moderator_id')
                    ->numeric(),
                Textarea::make('resolution_notes')
                    ->columnSpanFull(),
            ]);
    }
}



