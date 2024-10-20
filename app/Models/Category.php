<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];

    // Relationship with transactions as the source entity
    public function outgoingTransactions()
    {
        return $this->morphMany(Transaction::class, 'source');
    }

    // Relationship with transactions as the target entity
    public function incomingTransactions()
    {
        return $this->morphMany(Transaction::class, 'target');
    }

    public function parent_category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function sub_categories()
    {
        return $this->hasMany(Category::class);
    }
}
