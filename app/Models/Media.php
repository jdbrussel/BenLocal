<?php

namespace App\Models;

use App\Enums\ModerationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Translatable\HasTranslations;

class Media extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'model_type',
        'model_id',
        'collection',
        'file_path',
        'mime_type',
        'size',
        'width',
        'height',
        'alt_text',
        'uploaded_by',
        'moderation_status',
        'is_primary',
        'sort_order',
    ];

    public $translatable = ['alt_text'];

    protected $casts = [
        'is_primary' => 'boolean',
        'sort_order' => 'integer',
        'size' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
        'moderation_status' => ModerationStatus::class,
    ];

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
