<?php

namespace App\Models;

use App\Enums\ModerationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Review extends Model
{
    use HasFactory, HasTranslations, SoftDeletes;

    protected $fillable = [
        'user_id', 'spot_id', 'recommendation_id', 'spot_visit_id',
        'overall_rating', 'rating_values', 'review_text', 'original_language',
        'visited_at', 'user_region_status_at_time', 'user_community_id',
        'confirms_recommendation', 'perceived_community_profile',
        'visibility_score', 'moderation_status', 'flagged_count', 'verified_visit'
    ];

    public $translatable = ['review_text'];

    protected $casts = [
        'overall_rating' => 'decimal:2',
        'rating_values' => 'json',
        'visited_at' => 'datetime',
        'confirms_recommendation' => 'boolean',
        'perceived_community_profile' => 'json',
        'visibility_score' => 'decimal:2',
        'moderation_status' => ModerationStatus::class,
        'flagged_count' => 'integer',
        'verified_visit' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function spot()
    {
        return $this->belongsTo(Spot::class);
    }

    public function recommendation()
    {
        return $this->belongsTo(Recommendation::class);
    }

    public function community()
    {
        return $this->belongsTo(Community::class, 'user_community_id');
    }

    public function photos()
    {
        return $this->hasMany(ReviewPhoto::class);
    }

    public function reactions()
    {
        return $this->hasMany(ReviewReaction::class);
    }

    public function tags()
    {
        return $this->hasMany(ReviewUserTag::class);
    }

    public function taggedUsers()
    {
        return $this->belongsToMany(User::class, 'review_user_tags', 'review_id', 'tagged_user_id');
    }
}
