<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Place extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = ['area_id', 'name', 'slug', 'description', 'latitude', 'longitude', 'is_active'];

    public $translatable = ['name', 'description'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }
}
