<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserRegionStatus;
use App\Enums\UserRegionStatus as UserRegionStatusEnum;
use Illuminate\Support\Facades\DB;

class UserRegionStatusService
{
    /**
     * Update or create local status for a user in a region.
     */
    public function updateStatus(User $user, int $regionId, array $data = []): UserRegionStatus
    {
        return UserRegionStatus::updateOrCreate(
            ['user_id' => $user->id, 'region_id' => $regionId],
            [
                'status' => $data['status'] ?? UserRegionStatusEnum::TOURIST,
                'claimed_by_user' => $data['claimed_by_user'] ?? true,
                'residence_based' => $data['residence_based'] ?? false,
                'ip_supported' => $data['ip_supported'] ?? false,
                'confidence_score' => $data['confidence_score'] ?? 0,
                'verified_at' => $data['verified_at'] ?? null,
            ]
        );
    }

    /**
     * Auto-detect and set local status based on residence.
     */
    public function syncResidenceStatus(User $user): ?UserRegionStatus
    {
        if (!$user->residence_region_id) {
            return null;
        }

        return $this->updateStatus($user, $user->residence_region_id, [
            'status' => UserRegionStatusEnum::LOCAL,
            'residence_based' => true,
            'confidence_score' => 1.0, // High confidence since it's residence-based
            'verified_at' => now(),
        ]);
    }
}
