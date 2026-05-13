<?php

namespace App\Services\Gdpr;

use App\Models\PrivacyAuditLog;
use App\Models\User;
use Illuminate\Support\Facades\Request;

class ConsentAuditService
{
    public function logConsentUpdate(User $user, array $oldValues, array $newValues): void
    {
        PrivacyAuditLog::create([
            'user_id' => $user->id,
            'action' => 'consent_updated',
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    public function logVisibilityChange(User $user, array $oldValues, array $newValues): void
    {
        PrivacyAuditLog::create([
            'user_id' => $user->id,
            'action' => 'visibility_changed',
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    public function logAction(User $user, string $action, ?array $oldValues = null, ?array $newValues = null): void
    {
        PrivacyAuditLog::create([
            'user_id' => $user->id,
            'action' => $action,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
}
