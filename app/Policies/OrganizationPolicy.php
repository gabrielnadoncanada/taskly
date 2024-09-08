<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrganizationPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_organization');
    }

    public function view(User $user, Organization $model): bool
    {
        return $this->viewAny($user) || $this->viewSpecific($user, $model);
    }

    public function viewSpecific(User $user, Organization $model): bool
    {
        if ($user->can('view_organization')) {
            return true;
        }

        return $user->can('view_own_organization') && $user->organization_id === $model->id;
    }

    public function create(User $user): bool
    {
        return $user->can('create_organization');
    }

    public function update(User $user, Organization $model): bool
    {
        return $this->updateAny($user) || $this->updateOwn($user, $model);
    }

    public function updateAny(User $user): bool
    {
        return $user->can('update_organization');
    }

    public function updateOwn(User $user, Organization $model): bool
    {
        return $user->can('update_own_organization') && $user->organization_id === $model->id;
    }

    public function delete(User $user, Organization $model): bool
    {
        return $this->deleteAny($user) || $this->deleteSpecific($user, $model);
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_organization');
    }

    public function deleteSpecific(User $user, Organization $model): bool
    {
        return $user->can('delete_organization');
    }

    public function restore(User $user, Organization $model): bool
    {
        return $user->can('restore_organization');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_organization');
    }

    public function forceDelete(User $user, Organization $model): bool
    {
        return $user->can('force_delete_organization');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_organization');
    }
}
