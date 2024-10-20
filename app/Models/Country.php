<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    protected $guarded = [];

    public function wallets(): HasMany
    {
        return $this->hasMany(Wallet::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }

    public function outgoingTransactions(): HasMany
    {
        return $this->hasMany(Transaction::class)->whereIn('type',['expense', 'loan_to']);
    }

    public function incomingTransactions(): HasMany
    {
        return $this->hasMany(Transaction::class)->whereIn('type',['income', 'loan_from']);
    }

    public function getFactorAttribute()
    {
        if ($this->decimal_points == 2) {
            return 100;
        } else {
            return 1000;
        }
    }
}
