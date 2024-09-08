<?php

namespace App\Policies;

use App\Models\Item;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ItemPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_item');
    }

    public function view(User $user, Item $model): bool
    {
        return $this->viewAny($user) || $this->viewSpecific($user, $model);
    }

    public function viewSpecific(User $user, Item $model): bool
    {
        if ($user->can('view_item')) {
            return true;
        }

        return $user->can('view_own_item') && $user->id === $model->id;
    }

    public function create(User $user): bool
    {
        return $this->createAny($user);
    }

    public function createAny(User $user): bool
    {
        return $user->can('create_item');
    }

    public function update(User $user, Item $model): bool
    {
        return $this->updateAny($user, $model) || $this->updateOwn($user, $model);
    }

    public function updateAny(User $user, Item $model): bool
    {
        return $user->can('update_item');
    }

    public function updateOwn(User $user, Item $model): bool
    {
        return $user->can('update_own_item') && $user->id === $model->id;
    }

    public function delete(User $user, Item $model): bool
    {
        return $this->deleteAny($user) || $this->deleteSpecific($user, $model);
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_item');
    }

    public function deleteSpecific(User $user, Item $model): bool
    {
        return $user->can('delete_item');
    }

    public function restore(User $user, Item $model): bool
    {
        return $user->can('restore_item');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_item');
    }

    public function forceDelete(User $user, Item $model): bool
    {
        return $user->can('force_delete_item');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_item');
    }
}
