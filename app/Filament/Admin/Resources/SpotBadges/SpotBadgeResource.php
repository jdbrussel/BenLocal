<?php

namespace App\Filament\Admin\Resources\SpotBadges;

use App\Filament\Admin\Resources\SpotBadges\Pages\CreateSpotBadge;
use App\Filament\Admin\Resources\SpotBadges\Pages\EditSpotBadge;
use App\Filament\Admin\Resources\SpotBadges\Pages\ListSpotBadges;
use App\Filament\Support\TranslatableField;
use App\Models\SpotBadge;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;
use BackedEnum;

class SpotBadgeResource extends Resource
{
    protected static ?string $model = SpotBadge::class;

    protected static string|UnitEnum|null $navigationGroup = 'Spots';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('key')
                    ->required()
                    ->unique(ignoreRecord: true),
                TranslatableField::make('name')
                    ->columnSpanFull(),
                TranslatableField::make('description', 'Description', 'textarea')
                    ->columnSpanFull(),
                TextInput::make('icon'),
                ColorPicker::make('color'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('key')
                    ->searchable(),
                TextColumn::make('name.' . config('benlocal.default_language'))
                    ->label('Name')
                    ->searchable(),
                TextColumn::make('icon'),
                ColorColumn::make('color'),
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
            'index' => ListSpotBadges::route('/'),
            'create' => CreateSpotBadge::route('/create'),
            'edit' => EditSpotBadge::route('/{record}/edit'),
        ];
    }
}



