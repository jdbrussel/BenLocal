<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Area extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = ['region_id', 'name', 'slug', 'description', 'latitude', 'longitude', 'is_active'];

    public $translatable = ['name', 'description'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function places()
    {
        return $this->hasMany(Place::class);
    }

    public function spots()
    {
        return $this->hasMany(Spot::class);
    }
}
