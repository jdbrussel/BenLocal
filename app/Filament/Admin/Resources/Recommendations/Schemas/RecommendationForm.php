<?php

namespace App\Filament\Admin\Resources\Recommendations\Schemas;

use App\Enums\ModerationStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class RecommendationForm
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
                TextInput::make('region_id')
                    ->required()
                    ->numeric(),
                TextInput::make('community_id')
                    ->numeric(),
                Textarea::make('title')
                    ->columnSpanFull(),
                Textarea::make('motivation')
                    ->columnSpanFull(),
                TextInput::make('original_language'),
                TextInput::make('confidence_score')
                    ->numeric(),
                Toggle::make('hidden_gem_candidate')
                    ->required(),
                Select::make('moderation_status')
                    ->options(ModerationStatus::class),
            ]);
    }
}



