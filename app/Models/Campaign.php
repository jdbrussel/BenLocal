<?php

namespace App\Models;

use App\Enums\CampaignStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Campaign extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'name', 'slug', 'description', 'source_type', 'source_name', 'source_url',
        'region_id', 'sector_id', 'category_id', 'default_community_id',
        'landing_title', 'landing_intro', 'cta_text', 'success_message',
        'publication_context', 'starts_at', 'ends_at', 'requires_login',
        'requires_facebook_login', 'auto_create_spots', 'ai_enrichment_enabled',
        'notify_spot_by_email', 'is_active'
    ];

    public $translatable = [
        'name', 'description', 'landing_title', 'landing_intro',
        'cta_text', 'success_message', 'publication_context'
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'requires_login' => 'boolean',
        'requires_facebook_login' => 'boolean',
        'auto_create_spots' => 'boolean',
        'ai_enrichment_enabled' => 'boolean',
        'notify_spot_by_email' => 'boolean',
        'is_active' => 'boolean',
    ];

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

    public function defaultCommunity()
    {
        return $this->belongsTo(Community::class, 'default_community_id');
    }

    public function submissions()
    {
        return $this->hasMany(CampaignSubmission::class);
    }
}
