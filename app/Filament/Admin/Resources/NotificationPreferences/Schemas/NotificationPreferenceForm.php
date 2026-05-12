<?php

namespace App\Filament\Admin\Resources\NotificationPreferences\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class NotificationPreferenceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                Toggle::make('new_followers')
                    ->required(),
                Toggle::make('review_replies')
                    ->required(),
                Toggle::make('recommendation_validation')
                    ->required(),
                Toggle::make('tagged_in_review')
                    ->required(),
                Toggle::make('hidden_gem_updates')
                    ->required(),
                Toggle::make('local_status_updates')
                    ->required(),
                Toggle::make('spot_updates')
                    ->required(),
                Toggle::make('marketing')
                    ->required(),
                Toggle::make('email_enabled')
                    ->required(),
                Toggle::make('push_enabled')
                    ->required(),
            ]);
    }
}



