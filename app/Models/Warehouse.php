<?php

namespace App\Models;

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
class Warehouse extends Model
{
    use CanGetNamesStatically, HasFactory, SoftDeletes;

    protected $guarded = [];

    public const WAREHOUSE_PREFIX = 'WH';

    public const WAREHOUSE_NUMBER = 'warehouse_number';

    public const DISPLAY_WAREHOUSE_NUMBER = 'display_warehouse_number';

    public const NAME = 'name';

    public const ORGANIZATION_ID = 'organization_id';

    public function address(): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    public function localizations(): HasMany
    {
        return $this->hasMany(Localization::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function displayWarehouseNumber(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes): string => sprintf('%s%s', self::WAREHOUSE_PREFIX, $attributes[self::WAREHOUSE_NUMBER]),
        );
    }

    public static function generateWarehouseNumber($organization_id)
    {
        $lastWarehouse = self::where(self::ORGANIZATION_ID, $organization_id)->orderBy(self::WAREHOUSE_NUMBER, 'desc')->first();

        return $lastWarehouse ? $lastWarehouse->{self::WAREHOUSE_NUMBER} + 1 : 1;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($warehouse) {
            if (isset($warehouse->{self::ORGANIZATION_ID})) {
                $warehouse->{self::WAREHOUSE_NUMBER} = self::generateWarehouseNumber($warehouse->{self::ORGANIZATION_ID});
            }
        });
    }
}
