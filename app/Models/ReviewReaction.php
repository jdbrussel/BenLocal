<?php

namespace App\Models;

use App\Enums\ReviewReactionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewReaction extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'review_id', 'reaction', 'weight'];

    protected $casts = [
        'reaction' => ReviewReactionType::class,
        'weight' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function review()
    {
        return $this->belongsTo(Review::class);
    }
}
