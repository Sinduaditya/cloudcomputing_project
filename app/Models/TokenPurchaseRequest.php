<?php
// filepath: f:\UGM\cloudcomputing\cloudcomputing_project\app\Models\TokenPurchaseRequest.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TokenPurchaseRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'package_id',
        'package_name',
        'token_amount',
        'price',
        'discount',
        'payment_method',
        'status',
        'user_notes',
        'admin_notes',
        'payment_proof',
        'processed_by',
        'processed_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Get the user that owns the request
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who processed the request
     */
    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeAttribute()
    {
        return [
            'pending' => 'bg-warning',
            'approved' => 'bg-success',
            'rejected' => 'bg-danger',
            'cancelled' => 'bg-secondary',
        ][$this->status] ?? 'bg-secondary';
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    /**
     * Check if request can be processed
     */
    public function canBeProcessed()
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Scope for pending requests
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope for recent requests
     */
    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
