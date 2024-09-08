<?php

namespace App\Models;

use App\Enums\ItemStatus;
use App\Models\Scopes\TenantScope;
use App\Traits\CanGetNamesStatically;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ScopedBy([TenantScope::class])]

class Item extends Model
{
    use CanGetNamesStatically, HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        self::STATUS => ItemStatus::class,
        self::ITEM_NUMBER => 'integer',
    ];

    public const ITEM_PREFIX = 'ITEM';

    public const STATUS = 'status';

    public const ITEM_NUMBER = 'item_number';

    public const CUSTOMER_ID = 'customer_id';

    public const RECEIPT_ID = 'receipt_id';

    public const SHIPMENT_ID = 'shipment_id';

    public const QUANTITY = 'quantity';

    public const LOCALIZATION_ID = 'localization_id';

    public const WEIGHT = 'weight';

    public const WEIGHT_UNIT = 'weight_unit';

    public const WIDTH = 'width';

    public const LENGTH = 'length';

    public const HEIGHT = 'height';

    public const DIMENSION_UNIT = 'dimension_unit';

    public const DISPLAY_DIMENSIONS = 'display_dimensions';

    public const DISPLAY_WEIGHT = 'display_weight';

    public const DISPLAY_ITEM_NUMBER = 'display_item_number';

    public const DISPLAY_RECEIPT_ITEM_NUMBER = 'display_receipt_item_number';

    public const DISPLAY_LOCALIZATION_NUMBER = 'display_localization_number';

    public const AMOUNT = 'amount';

    public const DESCRIPTION = 'description';

    public const ORGANIZATION_ID = 'organization_id';

    public function receipt(): BelongsTo
    {
        return $this->belongsTo(Receipt::class);
    }

    public function shipment(): BelongsTo
    {
        return $this->belongsTo(Shipment::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function localization(): BelongsTo
    {
        return $this->belongsTo(Localization::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    protected function displayWeight(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes): string => $attributes[self::WEIGHT] ? sprintf(
                '%s %s',
                $attributes[self::WEIGHT],
                $attributes[self::WEIGHT_UNIT]
            ) : ''
        );
    }

    protected function displayDimensions(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes): string => isset($attributes[self::WIDTH], $attributes[self::LENGTH], $attributes[self::HEIGHT], $attributes[self::DIMENSION_UNIT])
                ? sprintf(
                    '%s %s x %s %s x %s %s',
                    $attributes[self::WIDTH],
                    $attributes[self::DIMENSION_UNIT],
                    $attributes[self::LENGTH],
                    $attributes[self::DIMENSION_UNIT],
                    $attributes[self::HEIGHT],
                    $attributes[self::DIMENSION_UNIT]
                )
                : ''
        );
    }

    public function displayItemNumber(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes): string => $this->formatItemNumber()
        );
    }

    public function formatItemNumber(): string
    {
        return sprintf(
            '%s%s',
            self::ITEM_PREFIX,
            $this->{self::ITEM_NUMBER}
        );
    }

    public static function generateItemNumber($organization_id)
    {
        $lastItem = self::where(self::ORGANIZATION_ID, $organization_id)
            ->orderBy(self::ITEM_NUMBER, 'desc')
            ->first();

        return $lastItem ? $lastItem->{self::ITEM_NUMBER} + 1 : 1;
    }

    protected function displayReceiptItemNumber(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes): string => sprintf(
                '%s %s',
                $this->receipt->{Receipt::DISPLAY_RECEIPT_NUMBER},
                $this->formatItemNumber()
            ),
        );
    }

    protected function displayLocalizationNumber(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes): string => $this->localization->{Localization::DISPLAY_LOCALIZATION_NUMBER}
        );
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($item) {
            if (isset($item->{self::ORGANIZATION_ID})) {
                $item->{self::ITEM_NUMBER} = self::generateItemNumber($item->{self::ORGANIZATION_ID});
            }
        });
    }
}
