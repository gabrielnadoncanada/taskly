<?php

namespace App\Models;

use App\Enums\TaskStatus;
use App\Models\Scopes\TenantScope;
use App\Models\Traits\AssignTenant;
use App\Traits\CanGetNamesStatically;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ScopedBy([TenantScope::class])]
class Task extends Model
{
    use AssignTenant, CanGetNamesStatically, HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        self::DATE => 'datetime',
        self::END_DATE => 'datetime',
        self::STATUS => TaskStatus::class,
    ];

    public const TITLE = 'title';

    public const PROJECT_ID = 'project_id';

    public const DESCRIPTION = 'description';

    public const ALL_DAY = 'all_day';

    public const DATE = 'date';

    public const END_DATE = 'end_date';

    public const ORDER = 'order';

    public const ESTIMATED_TIME = 'estimated_time';

    public const ACTUAL_TIME = 'actual_time';

    public const STATUS = 'status';

    public const ORGANIZATION_ID = 'organization_id';

    public const PARENT_TASK_ID = 'parent_task_id';

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function children()
    {
        return $this->hasMany(Task::class, 'parent_task_id');
    }

    public function parent()
    {
        return $this->belongsTo(Task::class, 'parent_task_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withTimestamps();
    }

    public function actionUsers()
    {
        return $this->belongsToMany(User::class, 'task_action_user')
            ->withPivot('action_required', 'scheduled_date')
            ->withTimestamps();
    }

    public function items()
    {
        return $this->belongsToMany(Item::class)
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
