<?php

namespace App\Filament\Admin\Resources\Follows\Pages;

use App\Filament\Admin\Resources\Follows\FollowResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFollow extends EditRecord
{
    protected static string $resource = FollowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}



