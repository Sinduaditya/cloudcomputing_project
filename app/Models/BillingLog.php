<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingLog extends Model
{
    use HasFactory;
    

    protected $fillable =[
        'user_id',
        'period_start',
        'period_end',
        'total_token',
        'total_mb'
    ];
}
