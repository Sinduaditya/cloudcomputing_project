<?php
// app/Models/TokenTransaction.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TokenTransaction extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'balance_after',
        'type',
        'description',
        'download_id',
        'admin_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function download()
    {
        return $this->belongsTo(Download::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function getTypeDescriptionAttribute()
    {
        $descriptions = [
            'admin_adjustment' => 'Admin adjustment',
            'download_cost' => 'Download cost',
            'refund' => 'Refund',
        ];

        return $descriptions[$this->type] ?? $this->type;
    }
}
