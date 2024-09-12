<?php

namespace App\Models;

use App\Enums\Language;
use App\Traits\CanGetNamesStatically;
use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasName, HasTenants
{
    use CanGetNamesStatically, HasFactory, HasPanelShield,HasRoles, Notifiable, SoftDeletes;

    public const ID = 'id';

    public const FIRST_NAME = 'first_name';

    public const LAST_NAME = 'last_name';

    public const OFFICE_PHONE = 'office_phone';

    public const NOTE = 'note';

    public const LANGUAGE = 'language';

    public const PHONE = 'phone';

    public const NAME = 'name';

    public const EMAIL = 'email';

    public const PASSWORD = 'password';

    public const REMEMBER_TOKEN = 'remember_token';

    public const EMAIL_VERIFIED_AT = 'email_verified_at';

    protected $hidden = [
        self::PASSWORD,
        self::REMEMBER_TOKEN,
    ];

    protected function casts(): array
    {
        return [
            self::EMAIL_VERIFIED_AT => 'datetime',
            self::PASSWORD => 'hashed',
            self::LANGUAGE => Language::class,
        ];
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
            return Organization::all();
        }

        return $this->organizations;
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return $this->organizations()->whereKey($tenant)->exists() ||
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

    // Tâches où l'utilisateur doit entreprendre une action
    public function actionTasks()
    {
        return $this->belongsToMany(Task::class, 'task_action_user')
            ->withPivot('action_required', 'scheduled_date')
            ->withTimestamps();
    }

    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(Organization::class);
    }
}
