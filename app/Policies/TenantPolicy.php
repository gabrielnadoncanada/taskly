<?php

namespace App\Policies;

use App\Models\User;
use Devlense\FilamentTenant\Models\Tenant;
use Illuminate\Auth\Access\HandlesAuthorization;

class TenantPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_tenant');
    }

    public function view(User $user, Tenant $model): bool
    {
        return $this->viewAny($user) || $this->viewSpecific($user, $model);
    }

    public function viewSpecific(User $user, Tenant $model): bool
    {
        if ($user->can('view_tenant')) {
            return true;
        }

        return $user->can('view_own_tenant') && $user->tenant_id === $model->id;
    }

    public function create(User $user): bool
    {
        return $user->can('create_tenant');
    }

    public function update(User $user, Tenant $model): bool
    {
        return $this->updateAny($user) || $this->updateOwn($user, $model);
    }

    public function updateAny(User $user): bool
    {
        return $user->can('update_tenant');
    }

    public function updateOwn(User $user, Tenant $model): bool
    {
        return $user->can('update_own_tenant') && $user->tenant_id === $model->id;
    }

    public function delete(User $user, Tenant $model): bool
    {
        return $this->deleteAny($user) || $this->deleteSpecific($user, $model);
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_tenant');
    }

    public function deleteSpecific(User $user, Tenant $model): bool
    {
        return $user->can('delete_tenant');
    }

    public function restore(User $user, Tenant $model): bool
    {
        return $user->can('restore_tenant');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_tenant');
    }

    public function forceDelete(User $user, Tenant $model): bool
    {
        return $user->can('force_delete_tenant');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_tenant');
    }
}
