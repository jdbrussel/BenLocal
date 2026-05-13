<?php

namespace App\Filament\Admin\Resources\GdprDeletions\Tables;

use App\Models\GdprDeletion;
use App\Services\Gdpr\AccountDeletionService;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GdprDeletionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('User')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('requested_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('anonymized_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('completed_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('process')
                    ->label('Process Deletion')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn (GdprDeletion $record, AccountDeletionService $service) => $service->processDeletion($record))
                    ->visible(fn (GdprDeletion $record) => $record->completed_at === null),
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}



