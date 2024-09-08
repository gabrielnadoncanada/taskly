<?php

namespace Database\Seeders\Authorizations;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    //Has access to Operation
    //Has access to Logistique
    //Has access to Administration
    //For every Organization
    private const SUPER_ADMIN_ROLE = 'Super Administrateur';

    //Has access to Operation
    //Has access to Logistique
    //Has access to Administration
    private const ADMIN_ROLE = 'Administrateur';

    private const MEMBER_ROLE = 'Membre';
    //Has access to Operation

    public function run()
    {
        $organizations = Organization::all();

        $this->seedRoles();

        foreach ($organizations as $organization) {

            $this->assignMembers($organization);
        }
    }

    private function seedRoles()
    {
        $roles = [self::ADMIN_ROLE, self::MEMBER_ROLE];

        foreach ($roles as $roleName) {
            Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web',
            ]);
        }
    }

    private function assignMembers(Organization $organization)
    {
        $this->assignSuperAdminRole();
        $this->assignAdminRole($organization);
        $this->assignMemberRole($organization);

    }

    private function assignSuperAdminRole()
    {
        $admin = User::where(User::EMAIL, 'admin@devactif.ca')->first();

        $admin->assignRole(self::SUPER_ADMIN_ROLE);

    }

    private function assignAdminRole(Organization $organization)
    {
        $member = $organization->users()->where(User::EMAIL, '!=', 'admin@devactif.ca')->first();

        if ($member != null) {
            $member->assignRole(self::ADMIN_ROLE);
        }
    }

    private function assignMemberRole(Organization $organization)
    {
        $members = $organization->users()->get();
        foreach ($members as $member) {
            if (! $member->hasRole(self::SUPER_ADMIN_ROLE) && ! $member->hasRole(self::ADMIN_ROLE)) {
                $member->assignRole(self::MEMBER_ROLE);
            }
        }
    }
}
