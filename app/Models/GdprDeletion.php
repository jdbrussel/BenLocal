<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GdprDeletion extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'requested_at', 'anonymized_at', 'completed_at'];

    protected $casts = [
        'requested_at' => 'datetime',
        'anonymized_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
