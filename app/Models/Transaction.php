<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Account;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    
    public const TYPES = [
        'TRANSFER_DEBIT',
        'TRANSFER_CREDIT',
        'FEE',
        'INTEREST',
        'FEE_FAILED'
    ];

   
    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function transfer(): BelongsTo
    {
        return $this->belongsTo(Transfer::class);
    }
}