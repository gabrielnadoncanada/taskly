<?php

namespace App\Models;

use Devlense\FilamentTenant\Concerns\MultiTenancy;
use Devlense\FilamentTenant\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Category extends Model
{
    use HasFactory, MultiTenancy, SoftDeletes;

    public $guarded = [];

    public const TITLE = 'title';

    public const DESCRIPTION = 'description';

    public const COLOR = 'color';

    public const TENANT_ID = 'tenant_id';

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }
}
