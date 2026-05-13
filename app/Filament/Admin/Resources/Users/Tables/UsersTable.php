<?php

namespace App\Filament\Admin\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('avatar')
                    ->searchable(),
                TextColumn::make('provider')
                    ->searchable(),
                TextColumn::make('provider_id')
                    ->searchable(),
                TextColumn::make('preferred_language')
                    ->searchable(),
                TextColumn::make('preferred_theme')
                    ->searchable(),
                TextColumn::make('country')
                    ->searchable(),
                TextColumn::make('city')
                    ->searchable(),
                TextColumn::make('residenceRegion.name')
                    ->searchable(),
                TextColumn::make('residenceArea.name')
                    ->searchable(),
                TextColumn::make('residencePlace.name')
                    ->searchable(),
                TextColumn::make('community.name')
                    ->searchable(),
                TextColumn::make('last_known_ip')
                    ->searchable(),
                TextColumn::make('last_known_country')
                    ->searchable(),
                TextColumn::make('last_known_region')
                    ->searchable(),
                TextColumn::make('local_status_verified_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('trust_penalty_score')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('suspended_until')
                    ->dateTime()
                    ->sortable(),
                IconColumn::make('is_shadowbanned')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}



