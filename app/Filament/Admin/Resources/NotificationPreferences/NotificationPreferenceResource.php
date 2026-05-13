<?php

namespace App\Filament\Admin\Resources\NotificationPreferences;

use Filament\Tables\Table;



use App\Filament\Admin\Resources\NotificationPreferences\Pages\CreateNotificationPreference;
use App\Filament\Admin\Resources\NotificationPreferences\Pages\EditNotificationPreference;
use App\Filament\Admin\Resources\NotificationPreferences\Pages\ListNotificationPreferences;
use App\Filament\Admin\Resources\NotificationPreferences\Schemas\NotificationPreferenceForm;
use App\Filament\Admin\Resources\NotificationPreferences\Tables\NotificationPreferencesTable;
use App\Models\NotificationPreference;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;


class NotificationPreferenceResource extends Resource
{
    protected static ?string $model = NotificationPreference::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(\Filament\Schemas\Schema $form): \Filament\Schemas\Schema
    {
        return NotificationPreferenceForm::configure($form);
    }

    public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table
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



