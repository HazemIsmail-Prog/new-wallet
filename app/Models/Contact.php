<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $guarded = [];

    // Relationship with transactions as the source entity
    public function outgoingTransactions()
    {
        return $this->morphMany(Transaction::class, 'target')->where('type','loan_from');
    }

    // Relationship with transactions as the target entity
    public function incomingTransactions()
    {
        return $this->morphMany(Transaction::class, 'target')->where('type','loan_to');
    }
}
