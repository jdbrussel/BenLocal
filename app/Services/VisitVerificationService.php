<?php

namespace App\Services;

use App\Models\Spot;

class VisitVerificationService
{
    /**
     * Verify GPS location against spot location.
     */
    public function verifyGps(Spot $spot, ?float $lat, ?float $lng): array
    {
        if (is_null($lat) || is_null($lng) || is_null($spot->latitude) || is_null($spot->longitude)) {
            return [
                'score' => 0.0,
                'is_suspicious' => true,
                'distance' => null,
            ];
        }

        $distance = $this->calculateDistance($lat, $lng, (float) $spot->latitude, (float) $spot->longitude);

        $isSuspicious = $distance > 500; // Further than 500m is suspicious
        $score = 0.0;

        if ($distance <= 100) {
            $score = 1.0;
        } elseif ($distance <= 300) {
            $score = 0.8;
        } elseif ($distance <= 500) {
            $score = 0.5;
        } else {
            $score = 0.1;
        }

        return [
            'score' => $score,
            'is_suspicious' => $isSuspicious,
            'distance' => $distance,
        ];
    }

    /**
     * Verify QR token.
     */
    public function verifyQr(Spot $spot, string $token): array
    {
        if (empty($spot->qr_token) || $spot->qr_token !== $token) {
            return [
                'score' => 0.0,
                'is_suspicious' => true,
            ];
        }

        return [
            'score' => 1.0,
            'is_suspicious' => false,
        ];
    }

    /**
     * Calculate distance between two points in meters (Haversine formula).
     */
    protected function calculateDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371000; // meters

        $latDelta = deg2rad($lat2 - $lat1);
        $lngDelta = deg2rad($lng2 - $lng1);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($lngDelta / 2) * sin($lngDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
