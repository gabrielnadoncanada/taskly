<?php

namespace App\Policies;

use App\Models\Receipt;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReceiptPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_receipt');
    }

    public function view(User $user, Receipt $model): bool
    {
        return $this->viewAny($user) || $this->viewSpecific($user, $model);
    }

    public function viewSpecific(User $user, Receipt $model): bool
    {
        if ($user->can('view_receipt')) {
            return true;
        }

        return $user->can('view_own_receipt') && $user->id === $model->id;
    }

    public function create(User $user): bool
    {
        return $user->can('create_receipt');
    }

    public function update(User $user, Receipt $model): bool
    {
        return $this->updateAny($user, $model) || $this->updateOwn($user, $model);
    }

    public function updateAny(User $user, Receipt $model): bool
    {
        return $user->can('update_receipt');
    }

    public function updateOwn(User $user, Receipt $model): bool
    {
        return $user->can('update_own_receipt') && $user->id === $model->id;
    }

    public function delete(User $user, Receipt $model): bool
    {
        return $this->deleteAny($user) || $this->deleteSpecific($user, $model);
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_receipt');
    }

    public function deleteSpecific(User $user, Receipt $model): bool
    {
        return $user->can('delete_receipt');
    }

    public function restore(User $user, Receipt $model): bool
    {
        return $user->can('restore_receipt');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_receipt');
    }

    public function forceDelete(User $user, Receipt $model): bool
    {
        return $user->can('force_delete_receipt');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_receipt');
    }
}
