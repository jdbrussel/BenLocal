<?php

namespace App\Filament\Admin\Resources\Spots\Schemas;

use App\Enums\SpotLifecycleStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SpotForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('name')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('slug')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('original_language'),
                TextInput::make('sector_id')
                    ->required()
                    ->numeric(),
                TextInput::make('category_id')
                    ->required()
                    ->numeric(),
                TextInput::make('region_id')
                    ->required()
                    ->numeric(),
                TextInput::make('area_id')
                    ->numeric(),
                TextInput::make('place_id')
                    ->numeric(),
                Textarea::make('address')
                    ->columnSpanFull(),
                TextInput::make('latitude')
                    ->numeric(),
                TextInput::make('longitude')
                    ->numeric(),
                TextInput::make('phone')
                    ->tel(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('website')
                    ->url(),
                Textarea::make('opening_hours')
                    ->columnSpanFull(),
                TextInput::make('price_level')
                    ->numeric(),
                Textarea::make('spec_values')
                    ->columnSpanFull(),
                TextInput::make('source'),
                TextInput::make('source_reference'),
                Select::make('lifecycle_status')
                    ->options(SpotLifecycleStatus::class)
                    ->required(),
                Toggle::make('is_claimed')
                    ->required(),
                DateTimePicker::make('claimed_at'),
                Toggle::make('verified_business')
                    ->required(),
                DateTimePicker::make('verified_at'),
                Toggle::make('ai_enriched')
                    ->required(),
                Textarea::make('ai_enrichment_data')
                    ->columnSpanFull(),
                TextInput::make('created_by')
                    ->numeric(),
            ]);
    }
}



