<?php

namespace App\Services;

use App\Models\ClaimToken;
use App\Models\Spot;
use App\Models\Campaign;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ClaimService
{
    /**
     * Generate a unique claim token for a spot.
     */
    public function generateToken(Spot $spot, ?string $email = null, ?Campaign $campaign = null, int $expiryDays = 30): ClaimToken
    {
        return ClaimToken::create([
            'spot_id' => $spot->id,
            'campaign_id' => $campaign?->id,
            'token' => Str::random(64),
            'email' => $email,
            'expires_at' => Carbon::now()->addDays($expiryDays),
        ]);
    }

    /**
     * Validate a claim token.
     */
    public function validateToken(string $token): ?ClaimToken
    {
        $claimToken = ClaimToken::where('token', $token)
            ->whereNull('used_at')
            ->where('expires_at', '>', Carbon::now())
            ->first();

        return $claimToken;
    }

    /**
     * Mark a token as used.
     */
    public function markTokenAsUsed(ClaimToken $token): void
    {
        $token->update(['used_at' => Carbon::now()]);
    }
}
