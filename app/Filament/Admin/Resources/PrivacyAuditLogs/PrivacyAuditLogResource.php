<?php

namespace App\Filament\Admin\Resources\PrivacyAuditLogs;

use App\Filament\Admin\Resources\PrivacyAuditLogs\Pages\ListPrivacyAuditLogs;
use App\Models\PrivacyAuditLog;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use App\Filament\Admin\Resources\PrivacyAuditLogs\Tables\PrivacyAuditLogsTable;

class PrivacyAuditLogResource extends Resource
{
    protected static ?string $model = PrivacyAuditLog::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-shield-check';

    protected static string|\UnitEnum|null $navigationGroup = 'GDPR';

    public static function table(Table $table): Table
    {
        return PrivacyAuditLogsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPrivacyAuditLogs::route('/'),
        ];
    }
}
