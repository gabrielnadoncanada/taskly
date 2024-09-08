<?php

namespace App\Models;

use App\Models\Scopes\TenantScope;
use App\Traits\CanGetNamesStatically;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ScopedBy([TenantScope::class])]
class Customer extends Model
{
    use CanGetNamesStatically, HasFactory, SoftDeletes;

    protected $guarded = [];

    public const CUSTOMER_PREFIX = 'CUST';

    public const CUSTOMER_NUMBER = 'customer_number';

    public const NAME = 'name';

    public const DISPLAY_NAME = 'display_name';

    public const DISPLAY_CUSTOMER_NUMBER = 'display_customer_number';

    public const ORGANIZATION_ID = 'organization_id';

    public function address(): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    public function contacts(): MorphMany
    {
        return $this->morphMany(Contact::class, 'contactable');
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function items()
    {
        return $this->hasManyThrough(Item::class, Receipt::class);
    }

    /** @return Attribute<string, never> */
    protected function displayName(): Attribute
    {
        $status = $this->deleted_at ? ' (désactivé)' : '';

        return Attribute::make(
            get: fn (mixed $value, array $attributes): string => sprintf('%s%s', $attributes['name'], $status)
        );
    }

    public function displayCustomerNumber(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes): string => sprintf('%s%s', self::CUSTOMER_PREFIX, $attributes[self::CUSTOMER_NUMBER]),
        );
    }

    public static function generateCustomerNumber($organization_id)
    {
        $lastCustomer = self::where(self::ORGANIZATION_ID, $organization_id)->orderBy(self::CUSTOMER_NUMBER, 'desc')->first();

        return $lastCustomer ? $lastCustomer->{self::CUSTOMER_NUMBER} + 1 : 1;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($customer) {

            if (isset($customer->{self::ORGANIZATION_ID})) {
                $customer->{self::CUSTOMER_NUMBER} = self::generateCustomerNumber($customer->{self::ORGANIZATION_ID});
            }
        });

        self::forceDeleted(function ($model) {
            $model->addresses()->forceDelete();
            $model->contacts()->forceDelete();
        });
    }
}
