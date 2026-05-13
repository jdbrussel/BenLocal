<?php

namespace App\Services\Gdpr;

use App\Models\User;
use Illuminate\Support\Str;

class UserAnonymizationService
{
    public function anonymize(User $user): void
    {
        $user->update([
            'name' => 'Deleted User',
            'email' => 'deleted_' . $user->id . '@example.com',
            'password' => bcrypt(Str::random(32)),
            'avatar' => null,
            'provider' => null,
            'provider_id' => null,
            'country' => null,
            'city' => null,
            'residence_region_id' => null,
            'residence_area_id' => null,
            'residence_place_id' => null,
            'last_known_ip' => null,
            'last_known_country' => null,
            'last_known_region' => null,
        ]);

        // Specific requirements: "user becomes Deleted user"
        // Reviews and recommendations may remain anonymized (staying linked to this anonymized user is fine)
    }
}
