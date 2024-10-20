<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Transaction extends Model
{
    protected $guarded = [];

    protected $casts = [
        'date' => 'datetime',
        'time' => 'datetime',
    ];

    public function wallet() : BelongsTo {
        return $this->belongsTo(Wallet::class);
    }

    public function target()
    {
        return $this->morphTo();
    }

    protected function amount(): Attribute
    {
        $selectedCountry = Country::find(Auth::user()->last_selected_country_id);

        return Attribute::make(
            get: fn ($value) => $value / $selectedCountry->factor,
            set: fn ($value) => $value * $selectedCountry->factor,
        );
    }
}
