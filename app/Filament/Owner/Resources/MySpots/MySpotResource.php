<?php

namespace App\Filament\Owner\Resources\MySpots;

use App\Filament\Owner\Resources\MySpots\Pages\CreateMySpot;
use App\Filament\Owner\Resources\MySpots\Pages\EditMySpot;
use App\Filament\Owner\Resources\MySpots\Pages\ListMySpots;
use App\Filament\Owner\Resources\MySpots\Schemas\MySpotForm;
use App\Filament\Owner\Resources\MySpots\Tables\MySpotsTable;
use App\Models\Spot;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MySpotResource extends Resource
{
    protected static ?string $model = Spot::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingStorefront;

    protected static ?string $navigationLabel = 'My Spots';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('owners', function ($query) {
                $query->where('user_id', auth()->id());
            });
    }

    public static function form(Schema $schema): Schema
    {
        return MySpotForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MySpotsTable::configure($table);
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
            'index' => ListMySpots::route('/'),
            'create' => CreateMySpot::route('/create'),
            'edit' => EditMySpot::route('/{record}/edit'),
        ];
    }
}
