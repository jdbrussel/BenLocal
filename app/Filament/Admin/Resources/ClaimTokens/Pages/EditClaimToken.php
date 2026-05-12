<?php

namespace App\Filament\Admin\Resources\ClaimTokens\Pages;

use App\Filament\Admin\Resources\ClaimTokens\ClaimTokenResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditClaimToken extends EditRecord
{
    protected static string $resource = ClaimTokenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}



