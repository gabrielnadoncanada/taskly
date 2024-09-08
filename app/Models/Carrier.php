<?php

namespace App\Models;

use App\Models\Scopes\TenantScope;
use App\Traits\CanGetNamesStatically;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ScopedBy([TenantScope::class])]
class Carrier extends Model
{
    use CanGetNamesStatically, HasFactory, SoftDeletes;

    protected $guarded = [];

    public const NAME = 'name';

    public const CARRIER_PREFIX = 'CAR';

    public const CARRIER_NUMBER = 'carrier_number';

    public const DISPLAY_CARRIER_NUMBER = 'display_carrier_number';

    public const ORGANIZATION_ID = 'organization_id';

    public function receipts(): HasMany
    {
        return $this->hasMany(Receipt::class);
    }

    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function displayCarrierNumber(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes): string => sprintf('%s%s', self::CARRIER_PREFIX, $attributes[self::CARRIER_NUMBER]),
        );
    }

    public static function generateCarrierNumber($organization_id)
    {
        $lastCarrier = self::where(self::ORGANIZATION_ID, $organization_id)->orderBy(self::CARRIER_NUMBER, 'desc')->first();

        return $lastCarrier ? $lastCarrier->{self::CARRIER_NUMBER} + 1 : 1;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($carrier) {
            if (isset($carrier->{self::ORGANIZATION_ID})) {
                $carrier->{self::CARRIER_NUMBER} = self::generateCarrierNumber($carrier->{self::ORGANIZATION_ID});
            }
            if (Filament::getTenant() != null) {
                $carrier->organization_id = Filament::getTenant()->id;
                $carrier->{self::CARRIER_NUMBER} = self::generateCarrierNumber($carrier->{self::ORGANIZATION_ID});
            }

        });
    }
}
