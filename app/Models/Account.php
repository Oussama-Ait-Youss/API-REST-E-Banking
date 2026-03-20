<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public const TYPES = ['COURANT', 'EPARGNE', 'MINEUR'];
    public const STATUSES = ['ACTIVE', 'BLOCKED', 'CLOSED'];

    protected $casts = [
        'balance' => 'decimal:2',
        'overdraft_limit' => 'decimal:2',
        'interest_rate' => 'decimal:2',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
                    ->using(AccountUser::class)
                    ->withPivot('accepted_closure')
                    ->withTimestamps();
    }

    public function guardian(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guardian_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
    
    public function blocks(): HasMany
    {
        return $this->hasMany(BlockedAccount::class);
    }

    public function sentTransfers(): HasMany
    {
        return $this->hasMany(Transfer::class, 'from_account_id');
    }

    public function receivedTransfers(): HasMany
    {
        return $this->hasMany(Transfer::class, 'to_account_id');
    }
}