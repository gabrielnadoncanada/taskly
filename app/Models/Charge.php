<?php

namespace App\Models;

use App\Models\Traits\AssignTenant;
use App\Traits\CanGetNamesStatically;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Charge extends Model
{
    use AssignTenant, CanGetNamesStatically;
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public const AMOUNT = 'amount';

    public const CURRENCY = 'currency';

    public const DATE = 'date';

    public const REFERENCE = 'reference';

    public const METHOD = 'method';

    public const STATUS = 'status';

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
