<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_project');
    }

    public function view(User $user, Project $model): bool
    {
        return $this->viewAny($user) || $this->viewSpecific($user, $model);
    }

    public function viewSpecific(User $user, Project $model): bool
    {
        if ($user->can('view_project')) {
            return true;
        }

        return $user->can('view_own_project') && $user->id === $model->id;
    }

    public function create(User $user): bool
    {
        return $user->can('create_project');
    }

    public function update(User $user, Project $model): bool
    {
        return $this->updateAny($user, $model) || $this->updateOwn($user, $model);
    }

    public function updateAny(User $user, Project $model): bool
    {
        return $user->can('update_project');
    }

    public function updateOwn(User $user, Project $model): bool
    {
        return $user->can('update_own_project') && $user->id === $model->id;
    }

    public function delete(User $user, Project $model): bool
    {
        return $this->deleteAny($user) || $this->deleteSpecific($user, $model);
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_project');
    }

    public function deleteSpecific(User $user, Project $model): bool
    {
        return $user->can('delete_project');
    }

    public function restore(User $user, Project $model): bool
    {
        return $user->can('restore_project');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_project');
    }

    public function forceDelete(User $user, Project $model): bool
    {
        return $user->can('force_delete_project');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_project');
    }
}
