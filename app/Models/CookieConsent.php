<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CookieConsent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'session_id', 'necessary', 'analytics',
        'personalization', 'marketing', 'ip_address', 'user_agent', 'consented_at'
    ];

    protected $casts = [
        'necessary' => 'boolean',
        'analytics' => 'boolean',
        'personalization' => 'boolean',
        'marketing' => 'boolean',
        'consented_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
