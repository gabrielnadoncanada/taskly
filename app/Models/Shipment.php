<?php

namespace App\Models;

use App\Enums\ShipmentStatus;
use App\Models\Scopes\TenantScope;
use App\Traits\CanGetNamesStatically;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ScopedBy([TenantScope::class])]
class Shipment extends Model
{
    use CanGetNamesStatically, HasFactory, SoftDeletes;

    protected $guarded = [];

    public const SHIPMENT_PREFIX = 'SHIP';

    public const PURCHASE_ORDER_IDENTIFIER = 'purchase_order_identifier';

    public const SHIPMENT_NUMBER = 'shipment_number';

    public const DISPLAY_SHIPMENT_NUMBER = 'display_shipment_number';

    public const CARRIER_ID = 'carrier_id';

    public const EXPECTED_DATE = 'expected_date';

    public const ADDRESS_ID = 'address_id';

    public const CUSTOMER_ID = 'customer_id';

    public const STATUS = 'status';

    public const DATE = 'date';

    public const ORGANIZATION_ID = 'organization_id';

    protected $casts = [
        self::STATUS => ShipmentStatus::class,
        self::SHIPMENT_NUMBER => 'integer',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function address(): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function carrier(): BelongsTo
    {
        return $this->belongsTo(Carrier::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    public function displayShipmentNumber(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes): string => sprintf('%s%s', self::SHIPMENT_PREFIX, $attributes[self::SHIPMENT_NUMBER]),
        );
    }

    public static function generateShipmentNumber($organization_id)
    {
        $lastShipment = self::where(self::ORGANIZATION_ID, $organization_id)->orderBy(self::SHIPMENT_NUMBER, 'desc')->first();

        return $lastShipment ? $lastShipment->{self::SHIPMENT_NUMBER} + 1 : 1;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($shipment) {
            if (isset($shipment->{self::ORGANIZATION_ID})) {
                $shipment->{self::SHIPMENT_NUMBER} = self::generateShipmentNumber($shipment->{self::ORGANIZATION_ID});
            }
        });
    }
}
