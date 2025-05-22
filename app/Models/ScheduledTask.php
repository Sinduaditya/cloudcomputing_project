<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduledTask extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'url', 'format', 'quality', 'scheduled_for', 'status', 'download_id', 'error_message'];

    protected $casts = [
        'scheduled_for' => 'datetime',
    ];

    /**
     * Get the platform based on URL
     */
    public function getPlatformAttribute()
    {
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
     * Get status badge class
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'scheduled' => 'bg-warning text-dark',
            'processing' => 'bg-info',
            'completed' => 'bg-success',
            'failed' => 'bg-danger',
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
}
