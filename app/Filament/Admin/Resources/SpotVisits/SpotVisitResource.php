<?php

namespace App\Filament\Admin\Resources\SpotVisits;

use App\Filament\Admin\Resources\SpotVisits\Pages\CreateSpotVisit;
use App\Filament\Admin\Resources\SpotVisits\Pages\EditSpotVisit;
use App\Filament\Admin\Resources\SpotVisits\Pages\ListSpotVisits;
use App\Models\SpotVisit;
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

class SpotVisitResource extends Resource
{
    protected static ?string $model = SpotVisit::class;

    protected static string|UnitEnum|null $navigationGroup = 'System';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-map';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Select::make('spot_id')
                    ->relationship('spot', 'name->' . config('benlocal.default_language'))
                    ->required(),
                TextInput::make('checked_in_at')
                    ->type('datetime-local')
                    ->required(),
                TextInput::make('visit_source'),
                TextInput::make('latitude')
                    ->numeric(),
                TextInput::make('longitude')
                    ->numeric(),
                TextInput::make('verification_score')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('User')
                    ->sortable(),
                TextColumn::make('spot.name.' . config('benlocal.default_language'))
                    ->label('Spot')
                    ->sortable(),
                TextColumn::make('visit_source'),
                TextColumn::make('verification_score'),
                TextColumn::make('checked_in_at')
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
            'index' => ListSpotVisits::route('/'),
            'create' => CreateSpotVisit::route('/create'),
            'edit' => EditSpotVisit::route('/{record}/edit'),
        ];
    }
}



