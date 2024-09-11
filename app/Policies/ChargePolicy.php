<?php

namespace App\Policies;

use App\Models\Charge;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChargePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_charge');
    }

    public function view(User $user, Charge $model): bool
    {
        return $this->viewAny($user) || $this->viewSpecific($user, $model);
    }

    public function viewSpecific(User $user, Charge $model): bool
    {
        if ($user->can('view_charge')) {
            return true;
        }

        return $user->can('view_own_charge') && $user->id === $model->id;
    }

    public function create(User $user): bool
    {
        return $user->can('create_charge');
    }

    public function update(User $user, Charge $model): bool
    {
        return $this->updateAny($user, $model) || $this->updateOwn($user, $model);
    }

    public function updateAny(User $user, Charge $model): bool
    {
        return $user->can('update_charge');
    }

    public function updateOwn(User $user, Charge $model): bool
    {
        return $user->can('update_own_charge') && $user->id === $model->id;
    }

    public function delete(User $user, Charge $model): bool
    {
        return $this->deleteAny($user) || $this->deleteSpecific($user, $model);
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_charge');
    }

    public function deleteSpecific(User $user, Charge $model): bool
    {
        return $user->can('delete_charge');
    }

    public function restore(User $user, Charge $model): bool
    {
        return $user->can('restore_charge');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_charge');
    }

    public function forceDelete(User $user, Charge $model): bool
    {
        return $user->can('force_delete_charge');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_charge');
    }
}
