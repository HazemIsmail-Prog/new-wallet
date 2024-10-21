<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];

    public function transactions()
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
