<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'provider',
        'provider_id',
        'preferred_language',
        'preferred_theme',
        'country',
        'city',
        'residence_region_id',
        'residence_area_id',
        'residence_place_id',
        'community_id',
        'last_known_ip',
        'last_known_country',
        'last_known_region',
        'local_status_verified_at',
        'trust_penalty_score',
        'suspended_until',
        'is_shadowbanned',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'local_status_verified_at' => 'datetime',
            'suspended_until' => 'datetime',
            'is_shadowbanned' => 'boolean',
            'trust_penalty_score' => 'integer',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    public function community(): BelongsTo
    {
        return $this->belongsTo(Community::class);
    }

    public function residenceRegion(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'residence_region_id');
    }

    public function residenceArea(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'residence_area_id');
    }

    public function residencePlace(): BelongsTo
    {
        return $this->belongsTo(Place::class, 'residence_place_id');
    }

    public function recommendations(): HasMany
    {
        return $this->hasMany(Recommendation::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function regionStatuses(): HasMany
    {
        return $this->hasMany(UserRegionStatus::class);
    }

    public function reputations(): HasMany
    {
        return $this->hasMany(UserReputation::class);
    }

    public function notificationPreferences(): HasOne
    {
        return $this->hasOne(NotificationPreference::class);
    }

    public function follows(): HasMany
    {
        return $this->hasMany(Follow::class, 'follower_id');
    }

    public function followers(): HasMany
    {
        return $this->hasMany(Follow::class, 'followed_id');
    }
}
