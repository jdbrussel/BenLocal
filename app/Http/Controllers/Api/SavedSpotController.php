<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Spot;
use App\Models\SavedSpot;
use App\Http\Resources\SpotListResource;
use Illuminate\Http\Request;

class SavedSpotController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $spots = Spot::whereHas('savedSpots', fn($q) => $q->where('user_id', $user->id))
            ->with(['category', 'region', 'area', 'place', 'badges', 'mainImage'])
            ->withCount(['recommendations', 'reviews'])
            ->withAvg('reviews', 'rating')
            ->get();

        foreach ($spots as $spot) {
            $spot->is_saved = true;
        }

        return SpotListResource::collection($spots);
    }

    public function store(Spot $spot, Request $request)
    {
        $request->user()->savedSpots()->updateOrCreate([
            'spot_id' => $spot->id
        ]);

        return response()->json(['message' => 'Spot saved successfully']);
    }

    public function destroy(Spot $spot, Request $request)
    {
        $request->user()->savedSpots()->where('spot_id', $spot->id)->delete();

        return response()->json(['message' => 'Spot removed from saved successfully']);
    }
}
