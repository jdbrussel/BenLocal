<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = ['sector_id', 'name', 'slug', 'description', 'icon', 'sort_order', 'is_active'];

    public $translatable = ['name', 'description'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }

    public function ratingSpecs()
    {
        return $this->hasMany(CategoryRatingSpec::class);
    }

    public function filterSpecs()
    {
        return $this->hasMany(CategoryFilterSpec::class);
    }
}
