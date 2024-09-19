<?php

namespace App\Models;

use App\Traits\CanGetNamesStatically;
use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Devlense\FilamentTenant\Concerns\Multitenancy;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasName;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasAvatar, HasName, HasTenants
{
    use CanGetNamesStatically, HasFactory, HasPanelShield, HasRoles, Notifiable, SoftDeletes;

    public const ID = 'id';

    public const FIRST_NAME = 'first_name';

    public const LAST_NAME = 'last_name';

    public const NOTE = 'note';

    public const RELATION_EMPLOYEE = 'employee';

    public const PHONE = 'phone';

    public const TENANT_ID = 'tenant_id';

    public const NAME = 'name';

    public const EMAIL = 'email';

    public const PASSWORD = 'password';

    public const REMEMBER_TOKEN = 'remember_token';

    public const EMAIL_VERIFIED_AT = 'email_verified_at';

    protected $guarded = [

    ];

    protected $hidden = [
        self::PASSWORD,
        self::REMEMBER_TOKEN,
    ];

    protected function casts(): array
    {
        return [
            self::EMAIL_VERIFIED_AT => 'datetime',
            self::PASSWORD => 'hashed',
        ];
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return null;
    }

    public function getFilamentName(): string
    {
        return "$this->first_name $this->last_name";
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn (): string => $this->first_name.' '.$this->last_name
        );
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function getTenants(Panel $panel): Collection
    {
        if ($this->hasRole('Super Administrateur')) {
            return Tenant::all();
        }

        return $this->tenants;
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return $this->tenants()->whereKey($tenant)->exists() ||
            $this->hasRole('Super Administrateur');
    }

    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'task_user')
            ->withTimestamps();
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_user')
            ->withTimestamps();
    }

    public function actionTasks()
    {
        return $this->belongsToMany(Task::class, 'task_action_user')
            ->withPivot('action_required', 'scheduled_date')
            ->withTimestamps();
    }

    public function tenants(): BelongsToMany
    {
        return $this->belongsToMany(Tenant::class);
    }

    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class, Employee::USER_ID, self::ID);
    }
}
