<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignRecommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id', 'submission_id', 'recommendation_id',
        'user_id', 'spot_id', 'selected_for_publication',
        'publication_status', 'internal_notes'
    ];

    protected $casts = [
        'selected_for_publication' => 'boolean',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function submission()
    {
        return $this->belongsTo(CampaignSubmission::class);
    }

    public function recommendation()
    {
        return $this->belongsTo(Recommendation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function spot()
    {
        return $this->belongsTo(Spot::class);
    }
}
