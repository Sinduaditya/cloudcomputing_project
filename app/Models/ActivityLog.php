<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'resource_id',
        'resource_type',
        'details',
        'ip_address'
    ];

    protected $casts = [
        'details' => 'json',
    ];

    const ACTION_LOGIN = 'login';
    const ACTION_REGISTER = 'register';
    const ACTION_DOWNLOAD_CREATE = 'download_create';
    const ACTION_DOWNLOAD_COMPLETE = 'download_complete';
    const ACTION_DOWNLOAD_FAIL = 'download_fail';
    const ACTION_SCHEDULE_CREATE = 'schedule_create';
    const ACTION_TOKEN_ADJUSTMENT = 'token_adjustment';

    /**
     * Get the user that owns the log
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get related resource (polymorphic)
     */
    public function resource()
    {
        if ($this->resource_type && $this->resource_id) {
            $className = '\\App\\Models\\' . $this->resource_type;
            if (class_exists($className)) {
                return $className::find($this->resource_id);
            }
        }
        return null;
    }

    /**
     * Get human-readable action description
     */
    public function getActionDescriptionAttribute()
    {
        $descriptions = [
            self::ACTION_LOGIN => 'Logged in',
            self::ACTION_REGISTER => 'Registered account',
            self::ACTION_DOWNLOAD_CREATE => 'Created download',
            self::ACTION_DOWNLOAD_COMPLETE => 'Completed download',
            self::ACTION_DOWNLOAD_FAIL => 'Failed download',
            self::ACTION_SCHEDULE_CREATE => 'Scheduled download',
            self::ACTION_TOKEN_ADJUSTMENT => 'Token adjustment',
        ];

        return $descriptions[$this->action] ?? $this->action;
    }

    /**
     * Get icon for action type
     */
    public function getActionIconAttribute()
    {
        $icons = [
            self::ACTION_LOGIN => 'fa-sign-in-alt',
            self::ACTION_REGISTER => 'fa-user-plus',
            self::ACTION_DOWNLOAD_CREATE => 'fa-plus-circle',
            self::ACTION_DOWNLOAD_COMPLETE => 'fa-check-circle',
            self::ACTION_DOWNLOAD_FAIL => 'fa-times-circle',
            self::ACTION_SCHEDULE_CREATE => 'fa-calendar-plus',
            self::ACTION_TOKEN_ADJUSTMENT => 'fa-coins',
        ];

        return 'fas ' . ($icons[$this->action] ?? 'fa-history');
    }

    /**
     * Get formatted creation time
     */
    public function getFormattedTimeAttribute()
    {
        return $this->created_at->format('d M Y H:i:s');
    }

    /**
     * Scope query to get recent activity
     */
    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }
}
