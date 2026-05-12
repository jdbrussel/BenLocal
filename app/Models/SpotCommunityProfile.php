<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpotCommunityProfile extends Model
{
    use HasFactory;

    protected $fillable = ['spot_id', 'community_id', 'percentage', 'confidence_score', 'source'];

    protected $casts = [
        'percentage' => 'decimal:2',
        'confidence_score' => 'decimal:2',
    ];

    public function spot()
    {
        return $this->belongsTo(Spot::class);
    }

    public function community()
    {
        return $this->belongsTo(Community::class);
    }
}
