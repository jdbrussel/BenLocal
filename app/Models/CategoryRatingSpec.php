<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class CategoryRatingSpec extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'category_id', 'key', 'name', 'description', 'type',
        'min_value', 'max_value', 'weight', 'sort_order', 'is_required', 'is_active'
    ];

    public $translatable = ['name', 'description'];

    protected $casts = [
        'is_required' => 'boolean',
        'is_active' => 'boolean',
        'weight' => 'decimal:2',
    ];

    public function options()
    {
        return $this->morphMany(CategorySpecOption::class, 'spec');
    }
}
