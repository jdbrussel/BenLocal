<?php

namespace App\Filament\Admin\Resources\GdprDeletions;

use App\Filament\Admin\Resources\GdprDeletions\Pages\CreateGdprDeletion;
use App\Filament\Admin\Resources\GdprDeletions\Pages\EditGdprDeletion;
use App\Filament\Admin\Resources\GdprDeletions\Pages\ListGdprDeletions;
use App\Filament\Admin\Resources\GdprDeletions\Schemas\GdprDeletionForm;
use App\Filament\Admin\Resources\GdprDeletions\Tables\GdprDeletionsTable;
use App\Models\GdprDeletion;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GdprDeletionResource extends Resource
{
    protected static ?string $model = GdprDeletion::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return GdprDeletionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GdprDeletionsTable::configure($table);
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
            'index' => ListGdprDeletions::route('/'),
            'create' => CreateGdprDeletion::route('/create'),
            'edit' => EditGdprDeletion::route('/{record}/edit'),
        ];
    }
}



