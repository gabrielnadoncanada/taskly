<?php

namespace App\Models;

use App\Traits\CanGetNamesStatically;
use Devlense\FilamentTenant\Concerns\MultiTenancy;
use Devlense\FilamentTenant\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use CanGetNamesStatically, HasFactory, MultiTenancy, SoftDeletes;

    public const ID = 'id';

    public const USER_ID = 'user_id';

    public const FIRST_NAME = 'first_name';

    public const LAST_NAME = 'last_name';

    public const POSITION = 'position';

    public const SALARY = 'salary';

    public const HIRED_AT = 'hired_at';

    public const CREATED_AT = 'created_at';

    public const UPDATED_AT = 'updated_at';

    public const RELATION_USER = 'user';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, self::USER_ID, User::ID);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
