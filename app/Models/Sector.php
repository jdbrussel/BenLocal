<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Sector extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = ['name', 'slug', 'description', 'icon', 'sort_order', 'is_active'];

    public $translatable = ['name', 'description'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}
