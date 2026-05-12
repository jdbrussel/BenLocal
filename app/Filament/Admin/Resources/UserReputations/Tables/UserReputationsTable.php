<?php

namespace App\Filament\Admin\Resources\UserReputations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UserReputationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('region_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('sector_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('category_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('community_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('local_status')
                    ->searchable(),
                TextColumn::make('recommendation_count')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('confirmed_recommendation_score')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('review_score')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('follower_count')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('hidden_gem_score')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('trust_score')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('rank')
                    ->numeric()
                    ->sortable(),
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



