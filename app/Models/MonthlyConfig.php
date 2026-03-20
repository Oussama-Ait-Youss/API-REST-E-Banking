<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlyConfig extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'current_fee' => 'decimal:2',
        'daily_transfer_limit' => 'decimal:2',
    ];
}
