<?php

namespace App\Filament\Owner\Resources\Reviews\Tables;

use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ReviewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('spot.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('overall_rating')
                    ->label('Rating')
                    ->sortable(),
                TextColumn::make('review_text')
                    ->limit(50)
                    ->searchable(),
                IconColumn::make('owner_responded_at')
                    ->label('Responded')
                    ->boolean()
                    ->getStateUsing(fn ($record) => $record->owner_responded_at !== null),
                TextColumn::make('visited_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make()
                    ->label(fn ($record) => $record->owner_responded_at ? 'Edit Response' : 'Respond'),
            ])
            ->bulkActions([
                //
            ]);
    }
}
