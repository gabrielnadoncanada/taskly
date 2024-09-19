<?php

namespace App\Models;

use App\Traits\CanGetNamesStatically;
use Devlense\FilamentTenant\Concerns\MultiTenancy;
use Devlense\FilamentTenant\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Charge extends Model
{
    use CanGetNamesStatically, MultiTenancy;
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public const AMOUNT = 'amount';

    public const CURRENCY = 'currency';

    public const DATE = 'date';

    public const REFERENCE = 'reference';

    public const METHOD = 'method';

    public const STATUS = 'status';

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
