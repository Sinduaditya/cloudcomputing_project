<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Download extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'platform',
        'url',
        'title',
        'format',
        'quality',
        'duration',
        'file_path',
        'storage_url',
        'file_size',
        'token_cost',
        'status',
        'error_message',
        'completed_at'
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'file_size' => 'integer',
        'token_cost' => 'integer',
        'duration' => 'integer',
    ];

    const STATUS_STORING = 'storing';
    const STATUS_PENDING = 'pending';
    const STATUS_DOWNLOADING = 'downloading';
    const STATUS_UPLOADING = 'uploading';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_PAUSED = 'paused';

    /**
     * Get the user that owns the download
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the token transaction for this download
     */
    public function tokenTransaction()
    {
        return $this->hasOne(TokenTransaction::class, 'download_id');
    }

    /**
     * Get the scheduled task related to this download
     */
    public function scheduledTask()
    {
        return $this->hasOne(ScheduledTask::class);
    }

    /**
     * Format file size for human readability
     */
    public function getHumanFileSizeAttribute()
    {
        if (!$this->file_size) {
            return 'Unknown';
        }

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $size = $this->file_size;
        $i = 0;

        while ($size > 1024 && $i < count($units) - 1) {
            $size /= 1024;
            $i++;
        }

        return round($size, 2) . ' ' . $units[$i];
    }

    /**
     * Get Bootstrap badge class for status
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            self::STATUS_PENDING => 'badge-secondary',
            self::STATUS_DOWNLOADING => 'badge-info',
            self::STATUS_UPLOADING => 'badge-primary',
            self::STATUS_COMPLETED => 'badge-success',
            self::STATUS_FAILED => 'badge-danger',
            self::STATUS_CANCELLED => 'badge-warning',
            self::STATUS_PAUSED => 'badge-dark',
        ];

        return $badges[$this->status] ?? 'badge-secondary';
    }

    /**
     * Get the download URL with expiry time
     */
    public function getSecureDownloadUrlAttribute()
    {
        if (!$this->storage_url) {
            return null;
        }

        // For cloudinary URLs, they might already be secure
        if (strpos($this->storage_url, 'cloudinary') !== false) {
            return $this->storage_url;
        }

        // For local storage, generate a temporary URL
        if (strpos($this->file_path, 'public/') === 0) {
            return Storage::temporaryUrl(
                $this->file_path,
                now()->addHours(1)
            );
        }

        return $this->storage_url;
    }

    /**
     * Get duration in human readable format
     */
    public function getHumanDurationAttribute()
    {
        if (!$this->duration) {
            return 'Unknown';
        }

        $minutes = floor($this->duration / 60);
        $seconds = $this->duration % 60;

        return $minutes . ':' . str_pad($seconds, 2, '0', STR_PAD_LEFT);
    }

    /**
     * Check if download is complete
     */
    public function isComplete()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Check if download failed
     */
    public function isFailed()
    {
        return $this->status === self::STATUS_FAILED;
    }

    /**
     * Check if download is in progress
     */
    public function isInProgress()
    {
        return in_array($this->status, [
            self::STATUS_PENDING,
            self::STATUS_DOWNLOADING,
            self::STATUS_UPLOADING,
            self::STATUS_STORING,
        ]);
    }
}
