<?php

namespace App\Filament\Admin\Resources\GdprExports\Tables;

use App\Models\GdprExport;
use App\Services\Gdpr\DataExportService;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class GdprExportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('User')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('export_path')
                    ->searchable(),
                TextColumn::make('requested_at')
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
                    ->label('Process Export')
                    ->icon('heroicon-o-arrow-path')
                    ->action(fn (GdprExport $record, DataExportService $service) => $service->generateExport($record))
                    ->visible(fn (GdprExport $record) => $record->completed_at === null),
                Action::make('download')
                    ->label('Download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(fn (GdprExport $record) => Storage::disk('local')->download($record->export_path))
                    ->visible(fn (GdprExport $record) => $record->completed_at !== null),
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}



