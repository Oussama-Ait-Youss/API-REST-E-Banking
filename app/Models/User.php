<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guarded = ['id'];

    public const ROLES = ['client', 'admin'];

    protected $casts = [
        'birth_date' => 'date',
        'password' => 'hashed',
    ];

    public function accounts(): BelongsToMany
    {
        return $this->belongsToMany(Account::class)
                    ->using(AccountUser::class)
                    ->withPivot('accepted_closure')
                    ->withTimestamps();
    }

    public function guardedAccounts(): HasMany
    {
        return $this->hasMany(Account::class, 'guardian_id');
    }

    public function initiatedTransfers(): HasMany
    {
        return $this->hasMany(Transfer::class, 'initiated_by');
    }

    public function blockedAccounts(): HasMany
    {
        return $this->hasMany(BlockedAccount::class, 'admin_id');
    }
}