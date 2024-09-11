<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_task');
    }

    public function view(User $user, Task $model): bool
    {
        return $this->viewAny($user) || $this->viewSpecific($user, $model);
    }

    public function viewSpecific(User $user, Task $model): bool
    {
        if ($user->can('view_task')) {
            return true;
        }

        return $user->can('view_own_task') && $user->id === $model->id;
    }

    public function create(User $user): bool
    {
        return $user->can('create_task');
    }

    public function update(User $user, Task $model): bool
    {
        return $this->updateAny($user, $model) || $this->updateOwn($user, $model);
    }

    public function updateAny(User $user, Task $model): bool
    {
        return $user->can('update_task');
    }

    public function updateOwn(User $user, Task $model): bool
    {
        return $user->can('update_own_task') && $user->id === $model->id;
    }

    public function delete(User $user, Task $model): bool
    {
        return $this->deleteAny($user) || $this->deleteSpecific($user, $model);
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_task');
    }

    public function deleteSpecific(User $user, Task $model): bool
    {
        return $user->can('delete_task');
    }

    public function restore(User $user, Task $model): bool
    {
        return $user->can('restore_task');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_task');
    }

    public function forceDelete(User $user, Task $model): bool
    {
        return $user->can('force_delete_task');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_task');
    }
}
