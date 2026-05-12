<?php

namespace App\Filament\Admin\Resources\UserReputations\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserReputationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('region_id')
                    ->numeric(),
                TextInput::make('sector_id')
                    ->numeric(),
                TextInput::make('category_id')
                    ->numeric(),
                TextInput::make('community_id')
                    ->numeric(),
                TextInput::make('local_status'),
                TextInput::make('recommendation_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('confirmed_recommendation_score')
                    ->numeric(),
                TextInput::make('review_score')
                    ->numeric(),
                TextInput::make('follower_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('hidden_gem_score')
                    ->numeric(),
                TextInput::make('trust_score')
                    ->numeric(),
                TextInput::make('rank')
                    ->numeric(),
            ]);
    }
}



