<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Region;
use App\Http\Resources\RegionResource;
use App\Http\Resources\AreaResource;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    public function index()
    {
        $regions = Region::where('is_active', true)->withCount(['areas', 'spots'])->get();
        return RegionResource::collection($regions);
    }

    public function show(Region $region)
    {
        return new RegionResource($region->loadCount(['areas', 'spots']));
    }

    public function areas(Region $region)
    {
        return AreaResource::collection($region->areas()->where('is_active', true)->withCount(['places', 'spots'])->get());
    }
}
