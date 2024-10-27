<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];

    protected static function booted(): void
    {
        static::addGlobalScope(function (Builder $builder) {
            $builder->where('categories.country_id', session('activeCountry')->id);
        });

        static::creating(function ($model) {
            $model->country_id = session('activeCountry')->id;
        });
    }

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
