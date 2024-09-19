<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    private const SUPER_ADMIN_ROLE = 'Super Administrateur';

    private const ADMIN_ROLE = 'Administrateur';

    private const MEMBER_ROLE = 'Membre';

    public function run()
    {
        $this->assignPermissions();
    }

    /**
     * Assigner les permissions aux rôles.
     */
    private function assignPermissions()
    {
        $roles = $this->getRoles();
        $permissions = Permission::all();

        foreach ($permissions as $permission) {
            // Assigner toutes les permissions aux rôles Super Admin et Admin
            $roles['super_admin']->givePermissionTo($permission);
            $roles['admin']->givePermissionTo($permission);

            // Assigner certaines permissions au rôle Membre
            if ($this->hasMemberPermission($permission->name)) {
                $roles['member']->givePermissionTo($permission);
            }
        }
    }

    /**
     * Récupérer les rôles Super Admin, Admin et Membre.
     */
    private function getRoles(): array
    {
        return [
            'super_admin' => Role::where('name', self::SUPER_ADMIN_ROLE)->first(),
            'admin' => Role::where('name', self::ADMIN_ROLE)->first(),
            'member' => Role::where('name', self::MEMBER_ROLE)->first(),
        ];
    }

    /**
     * Vérifier si une permission doit être assignée au rôle Membre.
     */
    private function hasMemberPermission(string $permissionName): bool
    {
        $hasAccessToLogistique = str_contains($permissionName, 'customer') || str_contains($permissionName, 'warehouse') || str_contains($permissionName, 'carrier');
        $hasAccessToAdministration = str_contains($permissionName, 'role') || str_contains($permissionName, 'user');

        return ! $hasAccessToLogistique && ! $hasAccessToAdministration;
    }
}
