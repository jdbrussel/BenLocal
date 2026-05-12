<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'new_followers', 'review_replies', 'recommendation_validation',
        'tagged_in_review', 'hidden_gem_updates', 'local_status_updates',
        'spot_updates', 'marketing', 'email_enabled', 'push_enabled'
    ];

    protected $casts = [
        'new_followers' => 'boolean',
        'review_replies' => 'boolean',
        'recommendation_validation' => 'boolean',
        'tagged_in_review' => 'boolean',
        'hidden_gem_updates' => 'boolean',
        'local_status_updates' => 'boolean',
        'spot_updates' => 'boolean',
        'marketing' => 'boolean',
        'email_enabled' => 'boolean',
        'push_enabled' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
