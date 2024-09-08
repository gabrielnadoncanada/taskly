<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Auth\Access\HandlesAuthorization;

class WarehousePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_warehouse');
    }

    public function view(User $user, Warehouse $model): bool
    {
        return $this->viewAny($user) || $this->viewSpecific($user, $model);
    }

    public function viewSpecific(User $user, Warehouse $model): bool
    {
        if ($user->can('view_warehouse')) {
            return true;
        }

        return $user->can('view_own_warehouse') && $user->id === $model->id;
    }

    public function create(User $user): bool
    {
        return $user->can('create_warehouse');
    }

    public function update(User $user, Warehouse $model): bool
    {
        return $this->updateAny($user, $model) || $this->updateOwn($user, $model);
    }

    public function updateAny(User $user, Warehouse $model): bool
    {
        return $user->can('update_warehouse');
    }

    public function updateOwn(User $user, Warehouse $model): bool
    {
        return $user->can('update_own_warehouse') && $user->id === $model->id;
    }

    public function delete(User $user, Warehouse $model): bool
    {
        return $this->deleteAny($user) || $this->deleteSpecific($user, $model);
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_warehouse');
    }

    public function deleteSpecific(User $user, Warehouse $model): bool
    {
        return $user->can('delete_warehouse');
    }

    public function restore(User $user, Warehouse $model): bool
    {
        return $user->can('restore_warehouse');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_warehouse');
    }

    public function forceDelete(User $user, Warehouse $model): bool
    {
        return $user->can('force_delete_warehouse');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_warehouse');
    }
}
