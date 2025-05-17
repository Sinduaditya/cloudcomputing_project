<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduledTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'url',
        'platform',
        'format',
        'scheduled_for',
        'status',
    ];

    /**
     * Get the user that owns the scheduled task.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the activity logs for the scheduled task.
     */
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }
}
