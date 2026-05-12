<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sector;
use App\Models\Category;
use App\Http\Resources\SectorResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CategorySpecResource;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function sectors()
    {
        return SectorResource::collection(Sector::where('is_active', true)->with('categories')->get());
    }

    public function index()
    {
        return CategoryResource::collection(Category::where('is_active', true)->get());
    }

    public function show(Category $category)
    {
        return new CategoryResource($category->load(['filterSpecs.options', 'ratingSpecs.options']));
    }

    public function specs(Category $category)
    {
        $category->load(['filterSpecs.options', 'ratingSpecs.options']);

        return response()->json([
            'filter_specs' => CategorySpecResource::collection($category->filterSpecs),
            'rating_specs' => CategorySpecResource::collection($category->ratingSpecs),
        ]);
    }
}
