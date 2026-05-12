<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpotBadgeAssignment extends Model
{
    use HasFactory;

    protected $fillable = ['spot_id', 'badge_id', 'assigned_by', 'auto_assigned'];

    protected $casts = [
        'auto_assigned' => 'boolean',
    ];

    public function spot()
    {
        return $this->belongsTo(Spot::class);
    }

    public function badge()
    {
        return $this->belongsTo(SpotBadge::class, 'badge_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
