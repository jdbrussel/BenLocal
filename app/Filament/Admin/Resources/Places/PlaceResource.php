<?php

namespace App\Filament\Admin\Resources\Places;

use Filament\Tables\Table;



use App\Filament\Admin\Resources\Places\Pages\CreatePlace;
use App\Filament\Admin\Resources\Places\Pages\EditPlace;
use App\Filament\Admin\Resources\Places\Pages\ListPlaces;
use App\Filament\Support\TranslatableField;
use App\Models\Place;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;

use UnitEnum;
use BackedEnum;

class PlaceResource extends Resource
{
    protected static ?string $model = Place::class;

    protected static \UnitEnum|string|null $navigationGroup = 'Locations';

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-map-pin';

    public static function form(\Filament\Schemas\Schema $form): \Filament\Schemas\Schema
    {
        return $schema
            ->schema([
                Select::make('area_id')
                    ->relationship('area', 'name->' . config('benlocal.default_language'))
                    ->required()
                    ->searchable(),
                TranslatableField::make('name')
                    ->columnSpanFull(),
                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true),
                TranslatableField::make('description', 'Description', 'textarea')
                    ->columnSpanFull(),
                TextInput::make('latitude')
                    ->numeric(),
                TextInput::make('longitude')
                    ->numeric(),
                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
            ]);
    }

    public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('area.name.' . config('benlocal.default_language'))
                    ->label('Area')
                    ->sortable(),
                TextColumn::make('name.' . config('benlocal.default_language'))
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->searchable(),
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => ListPlaces::route('/'),
            'create' => CreatePlace::route('/create'),
            'edit' => EditPlace::route('/{record}/edit'),
        ];
    }
}



