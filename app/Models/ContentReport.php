<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'reporter_user_id', 'reportable_type', 'reportable_id',
        'reason', 'notes', 'status', 'moderator_id', 'resolution_notes'
    ];

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_user_id');
    }

    public function reportable()
    {
        return $this->morphTo();
    }

    public function moderator()
    {
        return $this->belongsTo(User::class, 'moderator_id');
    }
}
