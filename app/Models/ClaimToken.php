<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaimToken extends Model
{
    use HasFactory;

    protected $fillable = ['spot_id', 'campaign_id', 'token', 'email', 'expires_at', 'used_at'];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    public function spot()
    {
        return $this->belongsTo(Spot::class);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}
