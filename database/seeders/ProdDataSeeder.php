<?php

namespace Database\Seeders;

use App\Models\User;
use Devlense\FilamentTenant\Models\Tenant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class ProdDataSeeder extends Seeder
{
    private const TENANT_TITLE = 'Devactif';

    private const TENANT_EMAIL = 'root@devactif.ca';

    private const ADMIN_NAME = 'Admin';

    private const ADMIN_EMAIL = 'admin@devactif.ca';

    private const ADMIN_PASSWORD = 'password';

    private const ADMIN_ROLE = 'Super Administrateur';

    private const ROLES = ['Administrateur', 'Membre'];

    public function run()
    {
        Artisan::call('shield:generate --all');

        DB::transaction(function () {
            $this->seedRoles();
            $this->seedBaseTenant();
            $this->seedAdmin();
        });
    }

    /**
     * Seed the base TENANT.
     */
    protected function seedBaseTenant(): void
    {
        Tenant::firstOrCreate(
            [Tenant::TITLE => self::TENANT_TITLE],
            [Tenant::EMAIL => self::TENANT_EMAIL]
        );
    }

    /**
     * Seed the roles.
     */
    protected function seedRoles(): void
    {
        foreach (self::ROLES as $roleName) {
            Role::firstOrCreate(
                ['name' => $roleName],
                ['guard_name' => 'web']
            );
        }
    }

    protected function seedAdmin(): void
    {
        User::factory()->create([
            User::FIRST_NAME => self::ADMIN_NAME,
            User::EMAIL => self::ADMIN_EMAIL,
            User::PASSWORD => Hash::make(self::ADMIN_PASSWORD),
        ])->assignRole(self::ADMIN_ROLE);
    }
}
