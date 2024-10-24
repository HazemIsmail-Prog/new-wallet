<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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

    protected static function booted(): void
    {
        static::addGlobalScope(function (Builder $builder) {
            $builder->where('transactions.country_id', session('activeCountry')->id);
        });
    }

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public function target()
    {
        return $this->morphTo();
    }

    protected function amount(): Attribute
    {
        // Retrieve the country from the session
        $selectedCountry = session('activeCountry');

        // Check if selectedCountry is available and has the 'factor' property
        if (!$selectedCountry) {
            // Handle the case where the country is not set in the session
            // For example, you can return a default value or throw an exception
            throw new \Exception('No country selected in session.');
        }

        return Attribute::make(
            get: fn($value) => $value / $selectedCountry->factor,
            set: fn($value) => $value * $selectedCountry->factor,
        );
    }
}
