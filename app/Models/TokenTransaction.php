<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TokenTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'balance_after',
        'type',
        'description',
        'download_id',
        'admin_id',
        'resource_id',
        'resource_type',
    ];

    protected $casts = [
        'amount' => 'integer',
        'balance_after' => 'integer',
    ];

    const TYPE_INITIAL = 'initial';
    const TYPE_ADMIN_ADJUSTMENT = 'admin_adjustment';
    const TYPE_DOWNLOAD_COST = 'download_cost';
    const TYPE_REFUND = 'refund';

    /**
     * Get the user that owns the transaction
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the download associated with this transaction
     */
    public function download()
    {
        return $this->belongsTo(Download::class, 'download_id');
    }

    /**
     * Get the admin who performed the transaction
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Get descriptive text for transaction type
     */
    public function getTypeDescriptionAttribute()
    {
        $descriptions = [
            self::TYPE_INITIAL => 'Initial allocation',
            self::TYPE_ADMIN_ADJUSTMENT => 'Admin adjustment',
            self::TYPE_DOWNLOAD_COST => 'Download cost',
            self::TYPE_REFUND => 'Refund',
        ];

        return $descriptions[$this->type] ?? $this->type;
    }

    /**
     * Get CSS class for amount display
     */
    public function getAmountClassAttribute()
    {
        return $this->amount >= 0 ? 'text-success' : 'text-danger';
    }

    /**
     * Get formatted amount with sign
     */
    public function getFormattedAmountAttribute()
    {
        return ($this->amount >= 0 ? '+' : '') . $this->amount . ' tokens';
    }

    /**
     * Get icon for transaction type
     */
    public function getTypeIconAttribute()
    {
        $icons = [
            self::TYPE_INITIAL => 'fa-star',
            self::TYPE_ADMIN_ADJUSTMENT => 'fa-user-shield',
            self::TYPE_DOWNLOAD_COST => 'fa-download',
            self::TYPE_REFUND => 'fa-undo',
        ];

        return 'fas ' . ($icons[$this->type] ?? 'fa-exchange-alt');
    }
}
