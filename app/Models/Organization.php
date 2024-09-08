<?php

namespace App\Models;

use App\Enums\Currency;
use App\Enums\MeasurementSystem;
use App\Traits\CanGetNamesStatically;
use Filament\Models\Contracts\HasName;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model implements HasName
{
    use CanGetNamesStatically, HasFactory, SoftDeletes;

    protected $casts = [
        self::MEASUREMENT_SYSTEM => MeasurementSystem::class,
        self::CURRENCY => Currency::class,
    ];

    protected $guarded = [];

    public const TITLE = 'title';

    public const CURRENCY = 'currency';

    public const MEASUREMENT_SYSTEM = 'measurement_system';

    public const EMAIL = 'email';

    //region SCOPES

    //endregion

    //region RELATIONS

    public function morphAddresses(): MorphMany
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    public function localizations(): HasMany
    {
        return $this->hasMany(Localization::class);
    }

    public function carriers(): HasMany
    {
        return $this->hasMany(Carrier::class);
    }

    public function receipts(): HasMany
    {
        return $this->hasMany(Receipt::class);
    }

    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class);
    }

    public function warehouses(): HasMany
    {
        return $this->hasMany(Warehouse::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function getFilamentName(): string
    {
        return "$this->title";
    }
    //endregion

    //region ATTRIBUTES

    //endregion

    //region FUNCTIONS

    //endregion
}
