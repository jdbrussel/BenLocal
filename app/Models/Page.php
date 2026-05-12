<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Page extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'slug',
        'title',
        'intro',
        'content',
        'seo_title',
        'seo_description',
        'is_system_page',
        'published_at',
    ];

    public $translatable = [
        'title',
        'intro',
        'content',
        'seo_title',
        'seo_description',
    ];

    protected $casts = [
        'is_system_page' => 'boolean',
        'published_at' => 'datetime',
    ];
}
