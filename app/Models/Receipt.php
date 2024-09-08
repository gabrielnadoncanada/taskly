<?php

namespace App\Models;

use App\Enums\ReceiptStatus;
use App\Models\Scopes\TenantScope;
use App\Traits\CanGetNamesStatically;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ScopedBy([TenantScope::class])]
class Receipt extends Model
{
    use CanGetNamesStatically, HasFactory, SoftDeletes;

    protected $guarded = [];

    public const RECEIPT_PREFIX = 'REC';

    public const EXPECTED_DATE = 'expected_date';

    public const RECEIPT_NUMBER = 'receipt_number';

    public const DISPLAY_RECEIPT_NUMBER = 'display_receipt_number';

    public const PURCHASE_ORDER_IDENTIFIER = 'purchase_order_identifier';

    public const DISPLAY_RECEIPT_TITLE = 'display_receipt_title';

    public const CUSTOMER_ID = 'customer_id';

    public const CARRIER_ID = 'carrier_id';

    public const STATUS = 'status';

    public const DATE = 'date';

    public const ORGANIZATION_ID = 'organization_id';

    protected $casts = [
        self::STATUS => ReceiptStatus::class,
        self::DATE => 'date',
        self::RECEIPT_NUMBER => 'integer',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function carrier(): BelongsTo
    {
        return $this->belongsTo(Carrier::class);
    }

    public function displayReceiptTitle(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes): string => sprintf('%s (%s%s) ', $this->customer->display_name, self::RECEIPT_PREFIX, $attributes[self::RECEIPT_NUMBER]),
        );
    }

    public function displayReceiptNumber(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes): string => sprintf('%s%s', self::RECEIPT_PREFIX, $attributes[self::RECEIPT_NUMBER]),
        );
    }

    public static function generateReceiptNumber($organization_id)
    {
        $lastReceipt = self::where(self::ORGANIZATION_ID, $organization_id)->orderBy(self::RECEIPT_NUMBER, 'desc')->first();

        return $lastReceipt ? $lastReceipt->{self::RECEIPT_NUMBER} + 1 : 1;
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($receipt) {

            if (isset($receipt->{self::ORGANIZATION_ID})) {
                $receipt->{self::RECEIPT_NUMBER} = self::generateReceiptNumber($receipt->{self::ORGANIZATION_ID});
            }
        });
    }
}
