<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class SpotBadge extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = ['key', 'name', 'description', 'icon', 'color'];

    public $translatable = ['name', 'description'];

    public function spots()
    {
        return $this->belongsToMany(Spot::class, 'spot_badge_assignments', 'badge_id', 'spot_id');
    }
}
