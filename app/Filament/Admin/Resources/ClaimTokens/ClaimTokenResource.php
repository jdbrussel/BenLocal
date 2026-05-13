<?php

namespace App\Filament\Admin\Resources\ClaimTokens;

use Filament\Tables\Table;



use App\Filament\Admin\Resources\ClaimTokens\Pages\CreateClaimToken;
use App\Filament\Admin\Resources\ClaimTokens\Pages\EditClaimToken;
use App\Filament\Admin\Resources\ClaimTokens\Pages\ListClaimTokens;
use App\Models\ClaimToken;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;

use UnitEnum;
use BackedEnum;

class ClaimTokenResource extends Resource
{
    protected static ?string $model = ClaimToken::class;

    protected static \UnitEnum|string|null $navigationGroup = 'Business Claims';

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-key';

    public static function form(\Filament\Schemas\Schema $form): \Filament\Schemas\Schema
    {
        return $form
            ->schema([
                Select::make('spot_id')
                    ->relationship('spot', 'name->' . config('benlocal.default_language'))
                    ->required(),
                Select::make('campaign_id')
                    ->relationship('campaign', 'name->' . config('benlocal.default_language')),
                TextInput::make('token')
                    ->required(),
                TextInput::make('email')
                    ->email()
                    ->required(),
                TextInput::make('expires_at')
                    ->type('datetime-local')
                    ->required(),
                TextInput::make('used_at')
                    ->type('datetime-local'),
            ]);
    }

    public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('spot.name.' . config('benlocal.default_language'))
                    ->label('Spot'),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('token'),
                TextColumn::make('expires_at')
                    ->dateTime(),
                TextColumn::make('used_at')
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
            'index' => ListClaimTokens::route('/'),
            'create' => CreateClaimToken::route('/create'),
            'edit' => EditClaimToken::route('/{record}/edit'),
        ];
    }
}



