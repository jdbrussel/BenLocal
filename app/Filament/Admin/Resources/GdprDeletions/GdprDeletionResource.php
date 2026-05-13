<?php

namespace App\Filament\Admin\Resources\GdprDeletions;

use Filament\Tables\Table;



use App\Filament\Admin\Resources\GdprDeletions\Pages\CreateGdprDeletion;
use App\Filament\Admin\Resources\GdprDeletions\Pages\EditGdprDeletion;
use App\Filament\Admin\Resources\GdprDeletions\Pages\ListGdprDeletions;
use App\Filament\Admin\Resources\GdprDeletions\Schemas\GdprDeletionForm;
use App\Filament\Admin\Resources\GdprDeletions\Tables\GdprDeletionsTable;
use App\Models\GdprDeletion;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;


class GdprDeletionResource extends Resource
{
    protected static ?string $model = GdprDeletion::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-user-minus';

    protected static string|\UnitEnum|null $navigationGroup = 'GDPR';

    public static function form(\Filament\Schemas\Schema $form): \Filament\Schemas\Schema
    {
        return GdprDeletionForm::configure($form);
    }

    public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table
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



