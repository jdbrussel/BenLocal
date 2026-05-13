<?php

namespace App\Filament\Admin\Resources\CampaignRecommendations\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CampaignRecommendationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('campaign_id')
                    ->required()
                    ->numeric(),
                TextInput::make('submission_id')
                    ->required()
                    ->numeric(),
                TextInput::make('recommendation_id')
                    ->numeric(),
                TextInput::make('user_id')
                    ->numeric(),
                TextInput::make('spot_id')
                    ->numeric(),
                Toggle::make('selected_for_publication')
                    ->required(),
                TextInput::make('publication_status'),
                Textarea::make('internal_notes')
                    ->columnSpanFull(),
            ]);
    }
}
