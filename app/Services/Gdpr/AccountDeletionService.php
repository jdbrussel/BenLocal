<?php

namespace App\Services\Gdpr;

use App\Models\GdprDeletion;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AccountDeletionService
{
    public function __construct(
        protected UserAnonymizationService $anonymizationService,
        protected ConsentAuditService $auditService
    ) {}

    public function requestDeletion(User $user): GdprDeletion
    {
        return GdprDeletion::create([
            'user_id' => $user->id,
            'requested_at' => now(),
        ]);
    }

    public function processDeletion(GdprDeletion $deletion): void
    {
        DB::transaction(function () use ($deletion) {
            $user = $deletion->user;

            // Log the action before anonymizing
            $this->auditService->logAction($user, 'account_deleted');

            // Anonymize user
            $this->anonymizationService->anonymize($user);

            $deletion->update([
                'anonymized_at' => now(),
                'completed_at' => now(),
            ]);

            // Soft delete the user record to prevent logins
            $user->delete();
        });
    }
}
