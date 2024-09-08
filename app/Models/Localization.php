<?php

namespace App\Models;

use App\Traits\CanGetNamesStatically;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Localization extends Model
{
    use CanGetNamesStatically;
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public const WAREHOUSE_ID = 'warehouse_id';

    public const LOCATION_IDENTIFIER = 'location_identifier';

    public const LOCALIZATION_NUMBER = 'localization_number';

    public const DISPLAY_LOCALIZATION_NUMBER = 'display_localization_number';

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function displayLocalizationNumber(): Attribute
    {

        return Attribute::make(
            get: fn (mixed $value, array $attributes): string => sprintf('%s-%s', $this->warehouse->{Warehouse::DISPLAY_WAREHOUSE_NUMBER}, $attributes[self::LOCATION_IDENTIFIER]),
        );
    }
}
