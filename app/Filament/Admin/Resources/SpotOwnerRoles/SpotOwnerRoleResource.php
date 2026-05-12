<?php

namespace App\Filament\Admin\Resources\SpotOwnerRoles;

use App\Filament\Admin\Resources\SpotOwnerRoles\Pages\CreateSpotOwnerRole;
use App\Filament\Admin\Resources\SpotOwnerRoles\Pages\EditSpotOwnerRole;
use App\Filament\Admin\Resources\SpotOwnerRoles\Pages\ListSpotOwnerRoles;
use App\Models\SpotOwnerRole;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;
use BackedEnum;

class SpotOwnerRoleResource extends Resource
{
    protected static ?string $model = SpotOwnerRole::class;

    protected static string|UnitEnum|null $navigationGroup = 'Business Claims';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-identification';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('spot_id')
                    ->relationship('spot', 'name->' . config('benlocal.default_language'))
                    ->required(),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                TextInput::make('role')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('spot.name.' . config('benlocal.default_language'))
                    ->label('Spot')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('User')
                    ->sortable(),
                TextColumn::make('role')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
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
            'index' => ListSpotOwnerRoles::route('/'),
            'create' => CreateSpotOwnerRole::route('/create'),
            'edit' => EditSpotOwnerRole::route('/{record}/edit'),
        ];
    }
}



