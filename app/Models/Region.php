<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Region extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = ['name', 'slug', 'description', 'latitude', 'longitude', 'is_active'];

    public $translatable = ['name', 'description'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function areas()
    {
        return $this->hasMany(Area::class);
    }
}
