<?php

namespace App\Filament\Admin\Resources\UserRegionStatuses;

use App\Filament\Admin\Resources\UserRegionStatuses\Pages\CreateUserRegionStatus;
use App\Filament\Admin\Resources\UserRegionStatuses\Pages\EditUserRegionStatus;
use App\Filament\Admin\Resources\UserRegionStatuses\Pages\ListUserRegionStatuses;
use App\Models\UserRegionStatus;
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
use Filament\Tables\Table;
use UnitEnum;
use BackedEnum;

class UserRegionStatusResource extends Resource
{
    protected static ?string $model = UserRegionStatus::class;

    protected static string|UnitEnum|null $navigationGroup = 'Users & Trust';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-map';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Select::make('region_id')
                    ->relationship('region', 'name->' . config('benlocal.default_language'))
                    ->required(),
                TextInput::make('status')
                    ->required(),
                Toggle::make('claimed_by_user'),
                Toggle::make('residence_based'),
                Toggle::make('ip_supported'),
                Toggle::make('manually_verified'),
                TextInput::make('confidence_score')
                    ->numeric(),
                TextInput::make('verified_at')
                    ->type('datetime-local'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('User')
                    ->sortable(),
                TextColumn::make('region.name.' . config('benlocal.default_language'))
                    ->label('Region')
                    ->sortable(),
                TextColumn::make('status'),
                IconColumn::make('manually_verified')
                    ->boolean(),
                TextColumn::make('confidence_score'),
                TextColumn::make('verified_at')
                    ->dateTime(),
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
            'index' => ListUserRegionStatuses::route('/'),
            'create' => CreateUserRegionStatus::route('/create'),
            'edit' => EditUserRegionStatus::route('/{record}/edit'),
        ];
    }
}



