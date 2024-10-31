<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{
    protected $guarded = [];

    protected static function booted(): void
    {
        static::addGlobalScope(function (Builder $builder) {
            $builder->where('wallets.country_id', session('activeCountry')->id);
        });

        static::creating(function ($model) {
            $model->country_id = session('activeCountry')->id;
        });
    }

    // Relationship with transactions as the source entity
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'wallet_id');
    }

    // Relationship with transactions as the target entity
    public function incomingTransactions()
    {
        return $this->morphMany(Transaction::class, 'target');
    }
}
