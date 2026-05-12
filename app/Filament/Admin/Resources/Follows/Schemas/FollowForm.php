<?php

namespace App\Filament\Admin\Resources\Follows\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class FollowForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('follower_id')
                    ->required()
                    ->numeric(),
                TextInput::make('followed_id')
                    ->required()
                    ->numeric(),
            ]);
    }
}



