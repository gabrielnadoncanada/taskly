<?php

namespace App\Policies;

use App\Models\Carrier;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CarrierPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_carrier');
    }

    public function view(User $user, Carrier $model): bool
    {
        return $this->viewAny($user) || $this->viewSpecific($user, $model);
    }

    public function viewSpecific(User $user, Carrier $model): bool
    {
        if ($user->can('view_carrier')) {
            return true;
        }

        return $user->can('view_own_carrier') && $user->id === $model->id;
    }

    public function create(User $user): bool
    {
        return $user->can('create_carrier');
    }

    public function update(User $user, Carrier $model): bool
    {
        return $this->updateAny($user, $model) || $this->updateOwn($user, $model);
    }

    public function updateAny(User $user, Carrier $model): bool
    {
        return $user->can('update_carrier');
    }

    public function updateOwn(User $user, Carrier $model): bool
    {
        return $user->can('update_own_carrier') && $user->id === $model->id;
    }

    public function delete(User $user, Carrier $model): bool
    {
        return $this->deleteAny($user) || $this->deleteSpecific($user, $model);
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_carrier');
    }

    public function deleteSpecific(User $user, Carrier $model): bool
    {
        return $user->can('delete_carrier');
    }

    public function restore(User $user, Carrier $model): bool
    {
        return $user->can('restore_carrier');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_carrier');
    }

    public function forceDelete(User $user, Carrier $model): bool
    {
        return $user->can('force_delete_carrier');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_carrier');
    }
}
