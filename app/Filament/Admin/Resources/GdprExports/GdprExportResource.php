<?php

namespace App\Filament\Admin\Resources\GdprExports;

use App\Filament\Admin\Resources\GdprExports\Pages\CreateGdprExport;
use App\Filament\Admin\Resources\GdprExports\Pages\EditGdprExport;
use App\Filament\Admin\Resources\GdprExports\Pages\ListGdprExports;
use App\Filament\Admin\Resources\GdprExports\Schemas\GdprExportForm;
use App\Filament\Admin\Resources\GdprExports\Tables\GdprExportsTable;
use App\Models\GdprExport;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GdprExportResource extends Resource
{
    protected static ?string $model = GdprExport::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return GdprExportForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GdprExportsTable::configure($table);
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
            'index' => ListGdprExports::route('/'),
            'create' => CreateGdprExport::route('/create'),
            'edit' => EditGdprExport::route('/{record}/edit'),
        ];
    }
}



