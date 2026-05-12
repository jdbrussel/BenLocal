<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserReputation extends Model
{
    use HasFactory;

    protected $table = 'user_reputation';

    protected $fillable = [
        'user_id', 'region_id', 'sector_id', 'category_id', 'community_id',
        'local_status', 'recommendation_count', 'confirmed_recommendation_score',
        'review_score', 'follower_count', 'hidden_gem_score', 'trust_score', 'rank'
    ];

    protected $casts = [
        'confirmed_recommendation_score' => 'decimal:2',
        'review_score' => 'decimal:2',
        'hidden_gem_score' => 'decimal:2',
        'trust_score' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function community()
    {
        return $this->belongsTo(Community::class);
    }
}
