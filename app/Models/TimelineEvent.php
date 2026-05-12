<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimelineEvent extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $fillable = ['user_id', 'type', 'eventable_type', 'eventable_id', 'payload', 'region_id'];

    protected $casts = [
        'payload' => 'json',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function eventable()
    {
        return $this->morphTo();
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
