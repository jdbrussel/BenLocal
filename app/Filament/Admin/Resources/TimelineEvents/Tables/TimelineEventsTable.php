<?php

namespace App\Filament\Admin\Resources\TimelineEvents\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TimelineEventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('User')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'recommendation_created' => 'success',
                        'review_created' => 'info',
                        'review_reaction_created' => 'warning',
                        'follow_created' => 'primary',
                        default => 'gray',
                    })
                    ->searchable(),
                TextColumn::make('eventable_type')
                    ->label('Entity Type')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('eventable_id')
                    ->label('Entity ID')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('region.name')
                    ->label('Region')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'recommendation_created' => 'New Recommendation',
                        'review_created' => 'New Review',
                        'review_reaction_created' => 'Review Reaction',
                        'follow_created' => 'New Follow',
                    ]),
                SelectFilter::make('region_id')
                    ->relationship('region', 'name')
                    ->label('Region'),
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
