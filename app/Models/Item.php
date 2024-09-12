<?php

namespace App\Models;

use App\Enums\ItemStatus;
use App\Models\Scopes\TenantScope;
use App\Traits\CanGetNamesStatically;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ScopedBy([TenantScope::class])]
class Item extends Model
{
    use CanGetNamesStatically, HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        self::STATUS => ItemStatus::class,
        self::MEDIA => 'array',
    ];

    public const STATUS = 'status';

    public const TITLE = 'title';

    public const MEDIA = 'media';

    public const DESCRIPTION = 'description';

    public const WEIGHT = 'weight';

    public const DEFAULT_PRICE = 'default_price';

    public const SKU = 'sku';

    public const CATEGORY_ID = 'category_id';

    public const ORGANIZATION_ID = 'organization_id';

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class)
            ->withPivot('price')  // Include the 'price' field from the pivot table
            ->withTimestamps();
    }

    public function tasks()
    {
        return $this->belongsToMany(Task::class)
            ->withPivot('quantity')
            ->withTimestamps();
    }
}
