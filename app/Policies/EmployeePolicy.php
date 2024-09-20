<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_employee');
    }

    public function view(User $user, Employee $model): bool
    {
        return $this->viewAny($user) || $this->viewSpecific($user, $model);
    }

    public function viewSpecific(User $user, Employee $model): bool
    {
        if ($user->can('view_employee')) {
            return true;
        }

        return $user->can('view_own_employee') && $user->id === $model->id;
    }

    public function create(User $user): bool
    {
        return $user->can('create_employee');
    }

    public function update(User $user, Employee $model): bool
    {
        return $this->updateAny($user, $model) || $this->updateOwn($user, $model);
    }

    public function updateAny(User $user, Employee $model): bool
    {
        return $user->can('update_employee');
    }

    public function updateOwn(User $user, Employee $model): bool
    {
        return $user->can('update_own_employee') && $user->id === $model->id;
    }

    public function delete(User $user, Employee $model): bool
    {
        return $this->deleteAny($user) || $this->deleteSpecific($user, $model);
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_employee');
    }

    public function deleteSpecific(User $user, Employee $model): bool
    {
        return $user->can('delete_employee');
    }

    public function restore(User $user, Employee $model): bool
    {
        return $user->can('restore_employee');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_employee');
    }

    public function forceDelete(User $user, Employee $model): bool
    {
        return $user->can('force_delete_employee');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_employee');
    }
}
