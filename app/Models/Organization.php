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

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    public function suppliers(): HasMany
    {
        return $this->hasMany(Supplier::class);
    }

    public function getMeasurementSystemSuffix(): string
    {
        return $this->measurement_system === MeasurementSystem::METRIC ? 'kg' : 'lb';
    }

    public function getCurrencySymbol(): string
    {
        return $this->currency === Currency::CAD ? 'CAD$' : ($this->currency === Currency::USD ? 'USD$' : '$');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function getFilamentName(): string
    {
        return "$this->title";
    }
}
