<?php

namespace App\Filament\Admin\Resources\CategorySpecOptions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CategorySpecOptionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('spec_type')
                    ->required(),
                TextInput::make('spec_id')
                    ->required()
                    ->numeric(),
                TextInput::make('value')
                    ->required(),
                Textarea::make('label')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}



