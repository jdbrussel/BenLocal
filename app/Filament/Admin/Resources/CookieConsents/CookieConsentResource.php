<?php

namespace App\Filament\Admin\Resources\CookieConsents;

use App\Filament\Admin\Resources\CookieConsents\Pages\CreateCookieConsent;
use App\Filament\Admin\Resources\CookieConsents\Pages\EditCookieConsent;
use App\Filament\Admin\Resources\CookieConsents\Pages\ListCookieConsents;
use App\Models\CookieConsent;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;
use BackedEnum;

class CookieConsentResource extends Resource
{
    protected static ?string $model = CookieConsent::class;

    protected static string|UnitEnum|null $navigationGroup = 'CMS & Legal';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-shield-check';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name'),
                TextInput::make('session_id'),
                Toggle::make('necessary'),
                Toggle::make('analytics'),
                Toggle::make('personalization'),
                Toggle::make('marketing'),
                TextInput::make('ip_address'),
                TextInput::make('user_agent'),
                TextInput::make('consented_at')
                    ->type('datetime-local'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('User'),
                IconColumn::make('necessary')
                    ->boolean(),
                IconColumn::make('analytics')
                    ->boolean(),
                TextColumn::make('ip_address'),
                TextColumn::make('consented_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Read-only preferred
            ])
            ->bulkActions([
                //
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
            'index' => ListCookieConsents::route('/'),
            'create' => CreateCookieConsent::route('/create'),
            'edit' => EditCookieConsent::route('/{record}/edit'),
        ];
    }
}



