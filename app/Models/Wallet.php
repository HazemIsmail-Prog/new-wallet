<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{
    protected $guarded = [];

    // Relationship with transactions as the source entity
    public function transactions()
    {
        return $this->hasMany(Transaction::class,'wallet_id');
    }

    // Relationship with transactions as the target entity
    public function incomingTransactions()
    {
        return $this->morphMany(Transaction::class, 'target');
    }
}
