<?php

namespace App\Models;

use App\Traits\CanGetNamesStatically;
use Devlense\FilamentTenant\Concerns\MultiTenancy;
use Devlense\FilamentTenant\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use CanGetNamesStatically, HasFactory, MultiTenancy, SoftDeletes;

    protected $guarded = [];

    public const NAME = 'name';

    public const EMAIL = 'email';

    public const PHONE = 'phone';

    public const NOTE = 'note';

    public const TENANT_ID = 'tenant_id';

    public function addresses(): MorphMany
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
