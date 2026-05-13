<?php

namespace App\Filament\Admin\Resources\SpotClaims;

use App\Filament\Admin\Resources\SpotClaims\Pages\CreateSpotClaim;
use App\Filament\Admin\Resources\SpotClaims\Pages\EditSpotClaim;
use App\Filament\Admin\Resources\SpotClaims\Pages\ListSpotClaims;
use App\Filament\Admin\Resources\SpotClaims\Schemas\SpotClaimForm;
use App\Filament\Admin\Resources\SpotClaims\Tables\SpotClaimsTable;
use App\Models\SpotClaim;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SpotClaimResource extends Resource
{
    protected static ?string $model = SpotClaim::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    public static function form(Schema $schema): Schema
    {
        return SpotClaimForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SpotClaimsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSpotClaims::route('/'),
            'create' => CreateSpotClaim::route('/create'),
            'edit' => EditSpotClaim::route('/{record}/edit'),
        ];
    }
}
