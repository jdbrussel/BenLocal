<?php

namespace App\Filament\Admin\Resources\NotificationPreferences\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class NotificationPreferencesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('new_followers')
                    ->boolean(),
                IconColumn::make('review_replies')
                    ->boolean(),
                IconColumn::make('recommendation_validation')
                    ->boolean(),
                IconColumn::make('tagged_in_review')
                    ->boolean(),
                IconColumn::make('hidden_gem_updates')
                    ->boolean(),
                IconColumn::make('local_status_updates')
                    ->boolean(),
                IconColumn::make('spot_updates')
                    ->boolean(),
                IconColumn::make('marketing')
                    ->boolean(),
                IconColumn::make('email_enabled')
                    ->boolean(),
                IconColumn::make('push_enabled')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}



