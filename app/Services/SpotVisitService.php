<?php

namespace App\Services;

use App\Models\Spot;
use App\Models\SpotVisit;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SpotVisitService
{
    protected VisitVerificationService $verificationService;

    public function __construct(VisitVerificationService $verificationService)
    {
        $this->verificationService = $verificationService;
    }

    /**
     * Handle GPS check-in.
     */
    public function checkIn(User $user, Spot $spot, ?float $latitude, ?float $longitude): SpotVisit
    {
        $verification = $this->verificationService->verifyGps($spot, $latitude, $longitude);

        return DB::transaction(function () use ($user, $spot, $latitude, $longitude, $verification) {
            $visit = SpotVisit::create([
                'user_id' => $user->id,
                'spot_id' => $spot->id,
                'checked_in_at' => now(),
                'visit_source' => 'gps',
                'latitude' => $latitude,
                'longitude' => $longitude,
                'verification_score' => $verification['score'],
                'is_suspicious' => $verification['is_suspicious'],
                'metadata' => [
                    'distance_meters' => $verification['distance'] ?? null,
                ],
            ]);

            $this->updateReviewVerificationStatus($user, $spot, $visit);

            return $visit;
        });
    }

    /**
     * Handle QR check-in.
     */
    public function qrCheckIn(User $user, Spot $spot, string $token): SpotVisit
    {
        $verification = $this->verificationService->verifyQr($spot, $token);

        return DB::transaction(function () use ($user, $spot, $verification) {
            $visit = SpotVisit::create([
                'user_id' => $user->id,
                'spot_id' => $spot->id,
                'checked_in_at' => now(),
                'visit_source' => 'qr',
                'verification_score' => $verification['score'],
                'is_suspicious' => $verification['is_suspicious'],
            ]);

            $this->updateReviewVerificationStatus($user, $spot, $visit);

            return $visit;
        });
    }

    /**
     * Handle manual logging.
     */
    public function logManualVisit(User $user, Spot $spot, \DateTimeInterface $visitedAt): SpotVisit
    {
        return DB::transaction(function () use ($user, $spot, $visitedAt) {
            $visit = SpotVisit::create([
                'user_id' => $user->id,
                'spot_id' => $spot->id,
                'checked_in_at' => $visitedAt,
                'visit_source' => 'manual',
                'verification_score' => 0.2, // Manual is low trust
                'is_suspicious' => false,
            ]);

            $this->updateReviewVerificationStatus($user, $spot, $visit);

            return $visit;
        });
    }

    /**
     * If there's a recent review from this user for this spot, link it.
     * Or if we write a review later, it should look for a visit.
     */
    protected function updateReviewVerificationStatus(User $user, Spot $spot, SpotVisit $visit): void
    {
        if ($visit->is_suspicious || $visit->verification_score < 0.5) {
            return;
        }

        // Find a review written today or yesterday for this spot
        $review = $user->reviews()
            ->where('spot_id', $spot->id)
            ->where('created_at', '>=', now()->subDays(2))
            ->whereNull('spot_visit_id')
            ->first();

        if ($review) {
            $review->update([
                'spot_visit_id' => $visit->id,
                'verified_visit' => true,
            ]);
        }
    }
}
