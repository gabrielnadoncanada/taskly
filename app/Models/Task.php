<?php

namespace App\Models;

use App\Enums\TaskStatus;
use App\Traits\CanGetNamesStatically;
use Devlense\FilamentTenant\Concerns\MultiTenancy;
use Devlense\FilamentTenant\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use CanGetNamesStatically, HasFactory, MultiTenancy, SoftDeletes;

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

    public const TENANT_ID = 'tenant_id';

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

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
