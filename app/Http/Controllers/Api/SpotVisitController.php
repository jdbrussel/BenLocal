<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Spot;
use App\Services\SpotVisitService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpotVisitController extends Controller
{
    protected SpotVisitService $visitService;

    public function __construct(SpotVisitService $visitService)
    {
        $this->visitService = $visitService;
    }

    /**
     * List current user's visits.
     */
    public function index(Request $request)
    {
        $visits = $request->user()->spotVisits()
            ->with('spot:id,name,slug')
            ->latest('checked_in_at')
            ->paginate(20);

        return response()->json($visits);
    }

    /**
     * GPS Check-in.
     */
    public function checkIn(Request $request, Spot $spot)
    {
        $request->validate([
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $visit = $this->visitService->checkIn(
            $request->user(),
            $spot,
            $request->latitude,
            $request->longitude
        );

        return response()->json([
            'message' => 'Check-in successful',
            'visit' => $visit,
            'is_verified' => $visit->verification_score >= 0.5 && !$visit->is_suspicious,
        ], 201);
    }

    /**
     * QR Check-in.
     */
    public function qrCheckIn(Request $request, Spot $spot)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $visit = $this->visitService->qrCheckIn(
            $request->user(),
            $spot,
            $request->token
        );

        if ($visit->is_suspicious || $visit->verification_score < 0.5) {
            return response()->json([
                'message' => 'Invalid QR token',
                'visit' => $visit,
                'is_verified' => false,
            ], 422);
        }

        return response()->json([
            'message' => 'QR Check-in successful',
            'visit' => $visit,
            'is_verified' => true,
        ], 201);
    }
}
