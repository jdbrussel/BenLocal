<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class ReviewPhoto extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = ['review_id', 'path', 'caption', 'sort_order'];

    public $translatable = ['caption'];

    public function review()
    {
        return $this->belongsTo(Review::class);
    }
}
