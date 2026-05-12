<?php

namespace App\Filament\Admin\Resources\CampaignSubmissions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CampaignSubmissionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('campaign_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('submitted_name')
                    ->searchable(),
                TextColumn::make('submitted_place_hint')
                    ->searchable(),
                TextColumn::make('matched_spot_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_spot_id')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('user_confirmed_spot')
                    ->boolean(),
                IconColumn::make('wants_to_recommend')
                    ->boolean(),
                IconColumn::make('consent_to_contact')
                    ->boolean(),
                IconColumn::make('consent_to_publish_quote')
                    ->boolean(),
                IconColumn::make('consent_to_terms')
                    ->boolean(),
                TextColumn::make('status')
                    ->searchable(),
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
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}



