<?php

namespace App\Filament\Admin\Resources\PrivacyAuditLogs\Pages;

use App\Filament\Admin\Resources\PrivacyAuditLogs\PrivacyAuditLogResource;
use Filament\Resources\Pages\ListRecords;

class ListPrivacyAuditLogs extends ListRecords
{
    protected static string $resource = PrivacyAuditLogResource::class;
}
