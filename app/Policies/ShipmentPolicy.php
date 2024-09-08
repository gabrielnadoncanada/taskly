<?php

namespace App\Policies;

use App\Models\Shipment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ShipmentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_shipment');
    }

    public function view(User $user, Shipment $model): bool
    {
        return $this->viewAny($user) || $this->viewSpecific($user, $model);
    }

    public function viewSpecific(User $user, Shipment $model): bool
    {
        if ($user->can('view_shipment')) {
            return true;
        }

        return $user->can('view_own_shipment') && $user->id === $model->id;
    }

    public function create(User $user): bool
    {
        return $user->can('create_shipment');
    }

    public function update(User $user, Shipment $model): bool
    {
        return $this->updateAny($user, $model) || $this->updateOwn($user, $model);
    }

    public function updateAny(User $user, Shipment $model): bool
    {
        return $user->can('update_shipment');
    }

    public function updateOwn(User $user, Shipment $model): bool
    {
        return $user->can('update_own_shipment') && $user->id === $model->id;
    }

    public function delete(User $user, Shipment $model): bool
    {
        return $this->deleteAny($user) || $this->deleteSpecific($user, $model);
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_shipment');
    }

    public function deleteSpecific(User $user, Shipment $model): bool
    {
        return $user->can('delete_shipment');
    }

    public function restore(User $user, Shipment $model): bool
    {
        return $user->can('restore_shipment');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_shipment');
    }

    public function forceDelete(User $user, Shipment $model): bool
    {
        return $user->can('force_delete_shipment');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_shipment');
    }
}
