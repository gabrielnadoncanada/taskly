<?php

namespace Database\Seeders\Authorizations;

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

    private function assignPermissions()
    {
        $roleMember = Role::where('name', self::MEMBER_ROLE)->first();
        $roleAdmin = Role::where('name', self::ADMIN_ROLE)->first();
        $roleSuperAdmin = Role::where('name', self::SUPER_ADMIN_ROLE)->first();
        $permissions = Permission::all();

        foreach ($permissions as $permission) {

            $roleSuperAdmin->givePermissionTo($permission);
            $roleAdmin->givePermissionTo($permission);

            if ($this->hasMemberPermission($permission->name)) {
                $roleMember->givePermissionTo($permission);
            }
        }
    }

    private function hasMemberPermission(string $permissionName)
    {
        $hasAccessToLogistique = str_contains($permissionName, 'customer') || str_contains($permissionName, 'warehouse') || str_contains($permissionName, 'carrier');
        $hasAccessToAdministration = str_contains($permissionName, 'role') || str_contains($permissionName, 'user');

        return ! $hasAccessToLogistique && ! $hasAccessToAdministration;
    }
}
