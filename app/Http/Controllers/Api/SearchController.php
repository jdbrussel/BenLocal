<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Spot;
use App\Models\Region;
use App\Models\User;
use App\Http\Resources\SpotListResource;
use App\Http\Resources\RegionResource;
use App\Http\Resources\UserMiniResource;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $q = $request->q;
        $locale = app()->getLocale();

        if (!$q) {
            return response()->json([
                'spots' => [],
                'regions' => [],
                'users' => [],
            ]);
        }

        $spots = Spot::where('name->' . $locale, 'like', "%{$q}%")
            ->orWhere('name->en', 'like', "%{$q}%")
            ->where('lifecycle_status', 'active')
            ->limit(5)
            ->get();

        $regions = Region::where('name->' . $locale, 'like', "%{$q}%")
            ->orWhere('name->en', 'like', "%{$q}%")
            ->where('is_active', true)
            ->limit(3)
            ->get();

        $users = User::where('name', 'like', "%{$q}%")
            ->limit(5)
            ->get();

        return response()->json([
            'spots' => SpotListResource::collection($spots),
            'regions' => RegionResource::collection($regions),
            'users' => UserMiniResource::collection($users),
        ]);
    }
}
