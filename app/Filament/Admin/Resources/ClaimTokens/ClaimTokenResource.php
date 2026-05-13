<?php

namespace App\Filament\Admin\Resources\ClaimTokens;

use App\Filament\Admin\Resources\ClaimTokens\Pages\CreateClaimToken;
use App\Filament\Admin\Resources\ClaimTokens\Pages\EditClaimToken;
use App\Filament\Admin\Resources\ClaimTokens\Pages\ListClaimTokens;
use App\Filament\Admin\Resources\ClaimTokens\Schemas\ClaimTokenForm;
use App\Filament\Admin\Resources\ClaimTokens\Tables\ClaimTokensTable;
use App\Models\ClaimToken;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ClaimTokenResource extends Resource
{
    protected static ?string $model = ClaimToken::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedKey;

    public static function form(Schema $schema): Schema
    {
        return ClaimTokenForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ClaimTokensTable::configure($table);
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
            'index' => ListClaimTokens::route('/'),
            'create' => CreateClaimToken::route('/create'),
            'edit' => EditClaimToken::route('/{record}/edit'),
        ];
    }
}
