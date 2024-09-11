<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_category');
    }

    public function view(User $user, Category $model): bool
    {
        return $this->viewAny($user) || $this->viewSpecific($user, $model);
    }

    public function viewSpecific(User $user, Category $model): bool
    {
        if ($user->can('view_category')) {
            return true;
        }

        return $user->can('view_own_category') && $user->id === $model->id;
    }

    public function create(User $user): bool
    {
        return $user->can('create_category');
    }

    public function update(User $user, Category $model): bool
    {
        return $this->updateAny($user, $model) || $this->updateOwn($user, $model);
    }

    public function updateAny(User $user, Category $model): bool
    {
        return $user->can('update_category');
    }

    public function updateOwn(User $user, Category $model): bool
    {
        return $user->can('update_own_category') && $user->id === $model->id;
    }

    public function delete(User $user, Category $model): bool
    {
        return $this->deleteAny($user) || $this->deleteSpecific($user, $model);
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_category');
    }

    public function deleteSpecific(User $user, Category $model): bool
    {
        return $user->can('delete_category');
    }

    public function restore(User $user, Category $model): bool
    {
        return $user->can('restore_category');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_category');
    }

    public function forceDelete(User $user, Category $model): bool
    {
        return $user->can('force_delete_category');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_category');
    }
}
