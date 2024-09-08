<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_user');
    }

    public function view(User $user, User $model): bool
    {
        return $this->viewAny($user) || $this->viewSpecific($user, $model);
    }

    public function viewSpecific(User $user, User $model): bool
    {
        if ($user->can('view_user')) {
            return true;
        }

        return $user->can('view_own_user') && $user->id === $model->id;
    }

    public function create(User $user): bool
    {
        return $user->can('create_user');
    }

    public function update(User $user, User $model): bool
    {
        return $this->updateAny($user, $model) || $this->updateOwn($user, $model);
    }

    public function updateAny(User $user, User $model): bool
    {
        return $user->can('update_user');
    }

    public function updateOwn(User $user, User $model): bool
    {
        return $user->can('update_own_user') && $user->id === $model->id;
    }

    public function delete(User $user, User $model): bool
    {
        return $this->deleteAny($user) || $this->deleteSpecific($user, $model);
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_user');
    }

    public function deleteSpecific(User $user, User $model): bool
    {
        return $user->can('delete_user');
    }

    public function restore(User $user, User $model): bool
    {
        return $user->can('restore_user');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_user');
    }

    public function forceDelete(User $user, User $model): bool
    {
        return $user->can('force_delete_user');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_user');
    }
}
