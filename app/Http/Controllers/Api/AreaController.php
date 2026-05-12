<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Http\Resources\PlaceResource;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function places(Area $area)
    {
        return PlaceResource::collection($area->places()->where('is_active', true)->withCount(['spots'])->get());
    }
}
