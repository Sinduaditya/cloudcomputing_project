<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ScheduledTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'url',
        'format',
        'quality',
        'platform',
        'scheduled_for',
        'status',
        'download_id',
        'error_message',
        'completed_at',
        'failed_at'
    ];

    protected $casts = [
        'scheduled_for' => 'datetime',
        'completed_at' => 'datetime',
        'failed_at' => 'datetime',
    ];

    const STATUS_SCHEDULED = 'scheduled';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';

    // ...existing code...

    /**
     * Get the platform based on URL if not explicitly set
     */
    public function getPlatformAttribute($value)
    {
        if ($value) {
            return $value;
        }

        if (strpos($this->url, 'youtube') !== false || strpos($this->url, 'youtu.be') !== false) {
            return 'youtube';
        } elseif (strpos($this->url, 'instagram') !== false) {
            return 'instagram';
        } elseif (strpos($this->url, 'tiktok') !== false) {
            return 'tiktok';
        } elseif (strpos($this->url, 'facebook') !== false || strpos($this->url, 'fb.watch') !== false) {
            return 'facebook';
        }

        return 'other';
    }

    /**
     * Set the platform attribute
     */
    public function setPlatformAttribute($value)
    {
        $this->attributes['platform'] = $value ?: $this->getPlatformAttribute(null);
    }

    /**
     * Get Bootstrap badge class for status
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            self::STATUS_SCHEDULED => 'bg-warning text-dark',
            self::STATUS_PROCESSING => 'bg-info',
            self::STATUS_COMPLETED => 'bg-success',
            self::STATUS_FAILED => 'bg-danger',
            self::STATUS_CANCELLED => 'bg-secondary',
        ];

        return $badges[$this->status] ?? 'bg-secondary';
    }

    /**
     * Get user that owns this scheduled task
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get download result if processed
     */
    public function download()
    {
        return $this->belongsTo(Download::class, 'download_id');
    }

    /**
     * Get formatted scheduled time
     */
    public function getScheduledTimeAttribute()
    {
        return $this->scheduled_for->format('d M Y H:i');
    }

    /**
     * Get human-friendly time difference
     */
    public function getTimeRemainingAttribute()
    {
        if ($this->scheduled_for->isPast()) {
            return 'Waktu telah lewat';
        }

        return $this->scheduled_for->diffForHumans([
            'locale' => 'id',
            'syntax' => \Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW,
        ]);
    }

    /**
     * Check if task is ready to be processed
     */
    public function isReadyToProcess()
    {
        return $this->status === self::STATUS_SCHEDULED &&
               $this->scheduled_for->isPast();
    }

    /**
     * Check if task is completed
     */
    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Scope query to only include tasks ready to be processed
     */
    public function scopeReadyToProcess($query)
    {
        return $query->where('status', self::STATUS_SCHEDULED)
                     ->where('scheduled_for', '<=', now());
    }

    /**
     * Get formatted completion time
     */
    public function getCompletedTimeAttribute()
    {
        return $this->completed_at ? $this->completed_at->format('d M Y H:i') : null;
    }

    /**
     * Get formatted failure time
     */
    public function getFailedTimeAttribute()
    {
        return $this->failed_at ? $this->failed_at->format('d M Y H:i') : null;
    }
}
