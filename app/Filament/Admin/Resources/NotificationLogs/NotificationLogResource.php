<?php

namespace App\Filament\Admin\Resources\NotificationLogs;

use App\Filament\Admin\Resources\NotificationLogs\Pages\ManageNotificationLogs;
use Illuminate\Notifications\DatabaseNotification;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Schemas\Schema;

class NotificationLogResource extends Resource
{
    protected static ?string $model = DatabaseNotification::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-bell';

    protected static ?string $navigationLabel = 'Notification Logs';

    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
                // Read-only view
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('notifiable_type')->label('Model')->sortable(),
                TextColumn::make('notifiable.name')->label('Recipient')->sortable(),
                TextColumn::make('data.type')->label('Type')->sortable(),
                TextColumn::make('data.message')->label('Message')->limit(50),
                IconColumn::make('read_at')
                    ->label('Read')
                    ->boolean()
                    ->getStateUsing(fn ($record) => $record->read_at !== null),
                TextColumn::make('created_at')->label('Sent At')->dateTime()->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageNotificationLogs::route('/'),
        ];
    }
}
