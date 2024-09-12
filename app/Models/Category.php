<?php

namespace App\Models;

use App\Models\Scopes\TenantScope;
use App\Models\Traits\AssignTenant;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ScopedBy([TenantScope::class])]
final class Category extends Model
{
    use AssignTenant, HasFactory, SoftDeletes;

    public $guarded = [];

    public const TITLE = 'title';

    public const DESCRIPTION = 'description';

    public const COLOR = 'color';

    public const ORGANIZATION_ID = 'organization_id';

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }
}
