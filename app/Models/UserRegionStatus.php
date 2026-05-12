<?php

namespace App\Models;

use App\Enums\UserRegionStatus as UserRegionStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRegionStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'region_id', 'status', 'claimed_by_user',
        'residence_based', 'ip_supported', 'manually_verified',
        'confidence_score', 'verified_at'
    ];

    protected $casts = [
        'status' => UserRegionStatusEnum::class,
        'claimed_by_user' => 'boolean',
        'residence_based' => 'boolean',
        'ip_supported' => 'boolean',
        'manually_verified' => 'boolean',
        'confidence_score' => 'decimal:2',
        'verified_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
