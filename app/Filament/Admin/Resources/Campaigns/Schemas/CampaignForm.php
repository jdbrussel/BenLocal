<?php

namespace App\Filament\Admin\Resources\Campaigns\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CampaignForm
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
                TextInput::make('source_type'),
                TextInput::make('source_name'),
                TextInput::make('source_url')
                    ->url(),
                TextInput::make('region_id')
                    ->numeric(),
                TextInput::make('sector_id')
                    ->numeric(),
                TextInput::make('category_id')
                    ->numeric(),
                TextInput::make('default_community_id')
                    ->numeric(),
                Textarea::make('landing_title')
                    ->columnSpanFull(),
                Textarea::make('landing_intro')
                    ->columnSpanFull(),
                Textarea::make('cta_text')
                    ->columnSpanFull(),
                Textarea::make('success_message')
                    ->columnSpanFull(),
                Textarea::make('publication_context')
                    ->columnSpanFull(),
                DateTimePicker::make('starts_at'),
                DateTimePicker::make('ends_at'),
                Toggle::make('requires_login')
                    ->required(),
                Toggle::make('requires_facebook_login')
                    ->required(),
                Toggle::make('auto_create_spots')
                    ->required(),
                Toggle::make('ai_enrichment_enabled')
                    ->required(),
                Toggle::make('notify_spot_by_email')
                    ->required(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
