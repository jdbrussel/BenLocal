<?php

namespace App\Filament\Admin\Resources\Reviews\Schemas;

use App\Enums\ModerationStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ReviewForm
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
                TextInput::make('recommendation_id')
                    ->numeric(),
                TextInput::make('spot_visit_id')
                    ->numeric(),
                TextInput::make('overall_rating')
                    ->numeric(),
                Textarea::make('rating_values')
                    ->columnSpanFull(),
                Textarea::make('review_text')
                    ->columnSpanFull(),
                TextInput::make('original_language'),
                DateTimePicker::make('visited_at'),
                TextInput::make('user_region_status_at_time'),
                TextInput::make('user_community_id')
                    ->numeric(),
                Toggle::make('confirms_recommendation'),
                Textarea::make('perceived_community_profile')
                    ->columnSpanFull(),
                TextInput::make('visibility_score')
                    ->numeric(),
                Select::make('moderation_status')
                    ->options(ModerationStatus::class),
                TextInput::make('flagged_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('verified_visit')
                    ->required(),
            ]);
    }
}



