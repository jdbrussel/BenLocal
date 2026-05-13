<?php

namespace App\Models;

use App\Enums\SpotLifecycleStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Spot extends Model
{
    use HasFactory, HasTranslations, SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'description', 'original_language',
        'sector_id', 'category_id', 'region_id', 'area_id', 'place_id',
        'address', 'latitude', 'longitude', 'phone', 'email', 'website',
        'opening_hours', 'price_level', 'spec_values', 'source', 'source_reference',
        'lifecycle_status', 'is_claimed', 'claimed_at', 'verified_business', 'verified_at', 'qr_token',
        'ai_enriched', 'ai_enrichment_data', 'created_by', 'translated_at'
    ];

    public $translatable = ['name', 'description'];

    protected $casts = [
        'address' => 'json',
        'opening_hours' => 'json',
        'spec_values' => 'json',
        'ai_enrichment_data' => 'json',
        'lifecycle_status' => SpotLifecycleStatus::class,
        'is_claimed' => 'boolean',
        'claimed_at' => 'datetime',
        'verified_business' => 'boolean',
        'verified_at' => 'datetime',
        'ai_enriched' => 'boolean',
        'translated_at' => 'datetime',
    ];

    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function place()
    {
        return $this->belongsTo(Place::class);
    }

    public function communityProfiles()
    {
        return $this->hasMany(SpotCommunityProfile::class);
    }

    public function badges()
    {
        return $this->belongsToMany(SpotBadge::class, 'spot_badge_assignments', 'spot_id', 'badge_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'model');
    }

    public function mainImage()
    {
        return $this->morphOne(Media::class, 'model')->where('is_primary', true);
    }

    public function recommendations()
    {
        return $this->hasMany(Recommendation::class);
    }

    public function owners()
    {
        return $this->hasMany(SpotOwnerRole::class);
    }
}
