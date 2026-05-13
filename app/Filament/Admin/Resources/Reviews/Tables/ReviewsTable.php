<?php

namespace App\Filament\Admin\Resources\Reviews\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ReviewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('spot_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('recommendation_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('spot_visit_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('overall_rating')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('original_language')
                    ->searchable(),
                TextColumn::make('visited_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('user_region_status_at_time')
                    ->searchable(),
                TextColumn::make('user_community_id')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('confirms_recommendation')
                    ->boolean(),
                TextColumn::make('visibility_score')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('moderation_status')
                    ->badge()
                    ->searchable(),
                TextColumn::make('flagged_count')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('verified_visit')
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



