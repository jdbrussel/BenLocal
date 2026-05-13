<?php

namespace App\Filament\Admin\Resources\CampaignSubmissions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CampaignSubmissionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('campaign_id')
                    ->required()
                    ->numeric(),
                TextInput::make('user_id')
                    ->numeric(),
                TextInput::make('submitted_name')
                    ->required(),
                Textarea::make('submitted_notes')
                    ->columnSpanFull(),
                TextInput::make('submitted_place_hint'),
                TextInput::make('matched_spot_id')
                    ->numeric(),
                TextInput::make('created_spot_id')
                    ->numeric(),
                Textarea::make('ai_result')
                    ->columnSpanFull(),
                Toggle::make('user_confirmed_spot')
                    ->required(),
                Toggle::make('wants_to_recommend')
                    ->required(),
                Toggle::make('consent_to_contact')
                    ->required(),
                Toggle::make('consent_to_publish_quote')
                    ->required(),
                Toggle::make('consent_to_terms')
                    ->required(),
                TextInput::make('status'),
            ]);
    }
}
