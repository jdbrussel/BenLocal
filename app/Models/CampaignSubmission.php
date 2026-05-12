<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id', 'user_id', 'guest_token', 'submitted_name',
        'submitted_notes', 'submitted_place_hint', 'matched_spot_id',
        'created_spot_id', 'ai_result', 'user_confirmed_spot',
        'wants_to_recommend', 'consent_to_contact',
        'consent_to_publish_quote', 'consent_to_terms', 'status'
    ];

    protected $casts = [
        'ai_result' => 'json',
        'user_confirmed_spot' => 'boolean',
        'wants_to_recommend' => 'boolean',
        'consent_to_contact' => 'boolean',
        'consent_to_publish_quote' => 'boolean',
        'consent_to_terms' => 'boolean',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function matchedSpot()
    {
        return $this->belongsTo(Spot::class, 'matched_spot_id');
    }

    public function createdSpot()
    {
        return $this->belongsTo(Spot::class, 'created_spot_id');
    }
}
