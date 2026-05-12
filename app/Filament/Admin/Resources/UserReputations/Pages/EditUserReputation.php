<?php

namespace App\Filament\Admin\Resources\UserReputations\Pages;

use App\Filament\Admin\Resources\UserReputations\UserReputationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditUserReputation extends EditRecord
{
    protected static string $resource = UserReputationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}



