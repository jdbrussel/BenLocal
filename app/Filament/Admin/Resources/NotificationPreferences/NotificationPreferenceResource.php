<?php

namespace App\Filament\Admin\Resources\NotificationPreferences;

use App\Filament\Admin\Resources\NotificationPreferences\Pages\CreateNotificationPreference;
use App\Filament\Admin\Resources\NotificationPreferences\Pages\EditNotificationPreference;
use App\Filament\Admin\Resources\NotificationPreferences\Pages\ListNotificationPreferences;
use App\Filament\Admin\Resources\NotificationPreferences\Schemas\NotificationPreferenceForm;
use App\Filament\Admin\Resources\NotificationPreferences\Tables\NotificationPreferencesTable;
use App\Models\NotificationPreference;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class NotificationPreferenceResource extends Resource
{
    protected static ?string $model = NotificationPreference::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return NotificationPreferenceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NotificationPreferencesTable::configure($table);
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
            'index' => ListNotificationPreferences::route('/'),
            'create' => CreateNotificationPreference::route('/create'),
            'edit' => EditNotificationPreference::route('/{record}/edit'),
        ];
    }
}



