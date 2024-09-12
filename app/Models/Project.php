<?php

namespace App\Models;

use App\Models\Scopes\TenantScope;
use App\Traits\CanGetNamesStatically;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ScopedBy([TenantScope::class])]
class Project extends Model
{
    use CanGetNamesStatically, HasFactory, SoftDeletes;

    public const TITLE = 'title';

    public const DATE = 'date';

    public const DESCRIPTION = 'description';

    public const ORGANIZATION_ID = 'organization_id';

    public const STATUS = 'status';

    public const CLIENT_ID = 'client_id';

    protected $guarded = [];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
