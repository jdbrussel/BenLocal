<?php

namespace App\Filament\Admin\Resources\PrivacyAuditLogs\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PrivacyAuditLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('User')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('action')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('ip_address')
                    ->label('IP Address')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Logged At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
