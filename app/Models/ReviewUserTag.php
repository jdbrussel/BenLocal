<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewUserTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'review_id',
        'tagged_user_id',
        'tagged_by_user_id',
    ];

    public function review()
    {
        return $this->belongsTo(Review::class);
    }

    public function taggedUser()
    {
        return $this->belongsTo(User::class, 'tagged_user_id');
    }

    public function taggedBy()
    {
        return $this->belongsTo(User::class, 'tagged_by_user_id');
    }
}
