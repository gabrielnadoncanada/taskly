<?php

namespace App\Models;

use App\Traits\CanGetNamesStatically;
use Devlense\FilamentTenant\Concerns\MultiTenancy;
use Devlense\FilamentTenant\Models\Tenant;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Squire\Models\Region;

class Address extends Model
{
    use CanGetNamesStatically, Multitenancy;
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public const STREET = 'street';

    public const CITY = 'city';

    public const STATE = 'state';

    public const COUNTRY = 'country';

    public const POSTAL_CODE = 'postal_code';

    public const ADDRESSABLE_ID = 'addressable_id';

    public const ADDRESSABLE_TYPE = 'addressable_type';

    public const FULL_ADDRESS = 'full_address';

    public const DISPLAY_PROVINCE = 'display_province';

    public const DISPLAY_COUNTRY = 'display_country';

    public function addressable(): MorphTo
    {
        return $this->morphTo();
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'state');
    }

    protected function displayProvince(): Attribute
    {
        return Attribute::make(
            get: fn (): string => $this->region?->name
        );
    }

    protected function displayCountry(): Attribute
    {
        return Attribute::make(
            get: fn (): string => $this->region?->country?->name
        );
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /** @return Attribute<string, never> */
    protected function fullAddress(): Attribute
    {
        $status = $this->deleted_at ? ' (désactivée)' : '';

        return Attribute::make(
            get: fn (): string => sprintf(
                '%s, %s, %s %s%s',
                $this->{self::STREET},
                $this->{self::CITY},
                $this->region?->name,
                $this->{self::POSTAL_CODE},
                $status
            )
        );
    }
}
