<?php

namespace Database\Seeders;

use App\Enums\Currency;
use App\Enums\MeasurementSystem;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class ProdDataSeeder extends Seeder
{
    private const ORGANIZATION_TITLE = 'Devactif';

    private const ORGANIZATION_EMAIL = 'root@devactif.ca';

    private const ORGANIZATION_CURRENCY = Currency::CAD;

    private const ORGANIZATION_MEASUREMENT_SYSTEM = MeasurementSystem::METRIC;

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
            $this->seedBaseOrganization();
            $this->seedAdmin();
        });
    }

    /**
     * Seed the base organization.
     */
    protected function seedBaseOrganization(): void
    {
        Organization::firstOrCreate(
            [Organization::TITLE => self::ORGANIZATION_TITLE],
            [Organization::EMAIL => self::ORGANIZATION_EMAIL,
                Organization::CURRENCY => self::ORGANIZATION_CURRENCY,
                Organization::MEASUREMENT_SYSTEM => self::ORGANIZATION_MEASUREMENT_SYSTEM]
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
