<?php

namespace App\Policies;

use App\Models\Supplier;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SupplierPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_supplier');
    }

    public function view(User $user, Supplier $model): bool
    {
        return $this->viewAny($user) || $this->viewSpecific($user, $model);
    }

    public function viewSpecific(User $user, Supplier $model): bool
    {
        if ($user->can('view_supplier')) {
            return true;
        }

        return $user->can('view_own_supplier') && $user->id === $model->id;
    }

    public function create(User $user): bool
    {
        return $user->can('create_supplier');
    }

    public function update(User $user, Supplier $model): bool
    {
        return $this->updateAny($user, $model) || $this->updateOwn($user, $model);
    }

    public function updateAny(User $user, Supplier $model): bool
    {
        return $user->can('update_supplier');
    }

    public function updateOwn(User $user, Supplier $model): bool
    {
        return $user->can('update_own_supplier') && $user->id === $model->id;
    }

    public function delete(User $user, Supplier $model): bool
    {
        return $this->deleteAny($user) || $this->deleteSpecific($user, $model);
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_supplier');
    }

    public function deleteSpecific(User $user, Supplier $model): bool
    {
        return $user->can('delete_supplier');
    }

    public function restore(User $user, Supplier $model): bool
    {
        return $user->can('restore_supplier');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_supplier');
    }

    public function forceDelete(User $user, Supplier $model): bool
    {
        return $user->can('force_delete_supplier');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_supplier');
    }
}
