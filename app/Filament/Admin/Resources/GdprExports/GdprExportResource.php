<?php

namespace App\Filament\Admin\Resources\GdprExports;

use Filament\Tables\Table;



use App\Filament\Admin\Resources\GdprExports\Pages\CreateGdprExport;
use App\Filament\Admin\Resources\GdprExports\Pages\EditGdprExport;
use App\Filament\Admin\Resources\GdprExports\Pages\ListGdprExports;
use App\Filament\Admin\Resources\GdprExports\Schemas\GdprExportForm;
use App\Filament\Admin\Resources\GdprExports\Tables\GdprExportsTable;
use App\Models\GdprExport;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;


class GdprExportResource extends Resource
{
    protected static ?string $model = GdprExport::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-document-arrow-down';

    protected static string|\UnitEnum|null $navigationGroup = 'GDPR';

    public static function form(\Filament\Schemas\Schema $form): \Filament\Schemas\Schema
    {
        return GdprExportForm::configure($form);
    }

    public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table
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



