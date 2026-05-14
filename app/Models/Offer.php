<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Offer extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'spot_id',
        'title',
        'description',
        'coupon_code',
        'starts_at',
        'ends_at',
        'is_active',
    ];

    public $translatable = ['title', 'description'];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function spot()
    {
        return $this->belongsTo(Spot::class);
    }
}
