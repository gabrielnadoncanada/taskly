<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClientPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_client');
    }

    public function view(User $user, Client $model): bool
    {
        return $this->viewAny($user) || $this->viewSpecific($user, $model);
    }

    public function viewSpecific(User $user, Client $model): bool
    {
        if ($user->can('view_client')) {
            return true;
        }

        return $user->can('view_own_client') && $user->id === $model->id;
    }

    public function create(User $user): bool
    {
        return $user->can('create_client');
    }

    public function update(User $user, Client $model): bool
    {
        return $this->updateAny($user, $model) || $this->updateOwn($user, $model);
    }

    public function updateAny(User $user, Client $model): bool
    {
        return $user->can('update_client');
    }

    public function updateOwn(User $user, Client $model): bool
    {
        return $user->can('update_own_client') && $user->id === $model->id;
    }

    public function delete(User $user, Client $model): bool
    {
        return $this->deleteAny($user) || $this->deleteSpecific($user, $model);
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_client');
    }

    public function deleteSpecific(User $user, Client $model): bool
    {
        return $user->can('delete_client');
    }

    public function restore(User $user, Client $model): bool
    {
        return $user->can('restore_client');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_client');
    }

    public function forceDelete(User $user, Client $model): bool
    {
        return $user->can('force_delete_client');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_client');
    }
}
