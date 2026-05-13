<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Faq extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'question',
        'answer',
        'category',
        'sort_order',
        'is_active',
    ];

    public $translatable = [
        'question',
        'answer',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];
}
