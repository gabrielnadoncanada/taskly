<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_customer');
    }

    public function view(User $user, Customer $model): bool
    {
        return $this->viewAny($user) || $this->viewSpecific($user, $model);
    }

    public function viewSpecific(User $user, Customer $model): bool
    {
        if ($user->can('view_customer')) {
            return true;
        }

        return $user->can('view_own_customer') && $user->id === $model->id;
    }

    public function create(User $user): bool
    {
        return $user->can('create_customer');
    }

    public function update(User $user, Customer $model): bool
    {
        return $this->updateAny($user, $model) || $this->updateOwn($user, $model);
    }

    public function updateAny(User $user, Customer $model): bool
    {
        return $user->can('update_customer');
    }

    public function updateOwn(User $user, Customer $model): bool
    {
        return $user->can('update_own_customer') && $user->id === $model->id;
    }

    public function delete(User $user, Customer $model): bool
    {
        return $this->deleteAny($user) || $this->deleteSpecific($user, $model);
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_customer');
    }

    public function deleteSpecific(User $user, Customer $model): bool
    {
        return $user->can('delete_customer');
    }

    public function restore(User $user, Customer $model): bool
    {
        return $user->can('restore_customer');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_customer');
    }

    public function forceDelete(User $user, Customer $model): bool
    {
        return $user->can('force_delete_customer');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_customer');
    }
}
