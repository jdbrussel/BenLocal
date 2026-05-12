<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class CategorySpecOption extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = ['spec_type', 'spec_id', 'value', 'label', 'description', 'sort_order', 'is_active'];

    public $translatable = ['label', 'description'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function spec()
    {
        return $this->morphTo();
    }
}
