<?php

namespace App\Filament\Admin\Resources\UserRegionStatuses\Schemas;

use App\Enums\UserRegionStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserRegionStatusForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('region_id')
                    ->required()
                    ->numeric(),
                Select::make('status')
                    ->options(UserRegionStatus::class)
                    ->required(),
                Toggle::make('claimed_by_user')
                    ->required(),
                Toggle::make('residence_based')
                    ->required(),
                Toggle::make('ip_supported')
                    ->required(),
                Toggle::make('manually_verified')
                    ->required(),
                TextInput::make('confidence_score')
                    ->numeric(),
                DateTimePicker::make('verified_at'),
            ]);
    }
}



