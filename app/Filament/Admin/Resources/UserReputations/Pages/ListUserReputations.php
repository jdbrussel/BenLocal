<?php

namespace App\Filament\Admin\Resources\UserReputations\Pages;

use App\Filament\Admin\Resources\UserReputations\UserReputationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUserReputations extends ListRecords
{
    protected static string $resource = UserReputationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}



