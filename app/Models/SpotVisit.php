<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpotVisit extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'spot_id', 'checked_in_at', 'visit_source', 'latitude', 'longitude', 'verification_score'];

    protected $casts = [
        'checked_in_at' => 'datetime',
        'verification_score' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function spot()
    {
        return $this->belongsTo(Spot::class);
    }
}
