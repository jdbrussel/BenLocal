<?php

namespace App\Filament\Admin\Resources\ClaimTokens\Pages;

use App\Filament\Admin\Resources\ClaimTokens\ClaimTokenResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListClaimTokens extends ListRecords
{
    protected static string $resource = ClaimTokenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
