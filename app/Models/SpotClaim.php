<?php

namespace App\Models;

use App\Enums\ClaimStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpotClaim extends Model
{
    use HasFactory;

    protected $fillable = [
        'spot_id', 'user_id', 'business_name', 'contact_name',
        'email', 'phone', 'website', 'proof_document_path',
        'proof_notes', 'status', 'approved_by', 'approved_at', 'rejection_reason'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'status' => ClaimStatus::class,
    ];

    public function spot()
    {
        return $this->belongsTo(Spot::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
