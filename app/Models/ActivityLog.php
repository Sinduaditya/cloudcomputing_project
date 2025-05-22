<?php
// filepath: f:\UGM\cloudcomputing\cloudcomputing_project\app\Models\ActivityLog.php
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
