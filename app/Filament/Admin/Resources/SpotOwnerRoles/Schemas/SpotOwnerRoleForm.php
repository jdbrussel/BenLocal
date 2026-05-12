<?php

namespace App\Filament\Admin\Resources\SpotOwnerRoles\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SpotOwnerRoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('spot_id')
                    ->required()
                    ->numeric(),
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('role')
                    ->required(),
            ]);
    }
}



