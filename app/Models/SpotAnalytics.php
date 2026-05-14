<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpotAnalytics extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'spot_id',
        'metric_type',
        'user_id',
        'guest_token',
        'source',
        'metadata',
        'created_at',
    ];

    protected $casts = [
        'metadata' => 'json',
        'created_at' => 'datetime',
    ];

    public function spot()
    {
        return $this->belongsTo(Spot::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
