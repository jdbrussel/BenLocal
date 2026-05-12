<?php

namespace App\Models;

use App\Enums\ModerationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Recommendation extends Model
{
    use HasFactory, HasTranslations, SoftDeletes;

    protected $fillable = [
        'user_id', 'spot_id', 'region_id', 'community_id',
        'title', 'motivation', 'original_language',
        'confidence_score', 'hidden_gem_candidate', 'moderation_status'
    ];

    public $translatable = ['title', 'motivation'];

    protected $casts = [
        'confidence_score' => 'decimal:2',
        'hidden_gem_candidate' => 'boolean',
        'moderation_status' => ModerationStatus::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function spot()
    {
        return $this->belongsTo(Spot::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function community()
    {
        return $this->belongsTo(Community::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
