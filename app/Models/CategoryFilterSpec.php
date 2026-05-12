<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class CategoryFilterSpec extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'category_id', 'key', 'name', 'description', 'type',
        'unit', 'sort_order', 'is_required', 'is_filterable', 'is_active'
    ];

    public $translatable = ['name', 'description', 'unit'];

    protected $casts = [
        'is_required' => 'boolean',
        'is_filterable' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function options()
    {
        return $this->morphMany(CategorySpecOption::class, 'spec');
    }
}
