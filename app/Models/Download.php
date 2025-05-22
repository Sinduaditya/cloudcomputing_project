<?php
// filepath: f:\UGM\cloudcomputing\cloudcomputing_project\app\Models\Download.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tokenTransaction()
    {
        return $this->hasOne(TokenTransaction::class, 'download_id');
    }

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

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'badge-secondary',
            'downloading' => 'badge-info',
            'uploading' => 'badge-primary',
            'completed' => 'badge-success',
            'failed' => 'badge-danger'
        ];

        return $badges[$this->status] ?? 'badge-secondary';
    }
}
