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
        return \Illuminate\Support\Facades\Cache::tags(['categories'])->remember('sectors:active', now()->addDay(), function() {
            return SectorResource::collection(Sector::where('is_active', true)->with('categories')->get());
        });
    }

    public function index()
    {
        return \Illuminate\Support\Facades\Cache::tags(['categories'])->remember('categories:active', now()->addDay(), function() {
            return CategoryResource::collection(Category::where('is_active', true)->get());
        });
    }

    public function show(Category $category)
    {
        return \Illuminate\Support\Facades\Cache::tags(['categories'])->remember("category:{$category->id}", now()->addDay(), function() use ($category) {
            return new CategoryResource($category->load(['filterSpecs.options', 'ratingSpecs.options']));
        });
    }

    public function specs(Category $category)
    {
        return \Illuminate\Support\Facades\Cache::tags(['categories'])->remember("category_specs:{$category->id}", now()->addDay(), function() use ($category) {
            $category->load(['filterSpecs.options', 'ratingSpecs.options']);

            return [
                'filter_specs' => CategorySpecResource::collection($category->filterSpecs),
                'rating_specs' => CategorySpecResource::collection($category->ratingSpecs),
            ];
        });
    }
}
