<?php

namespace Database\Seeders;

use Database\Seeders\Authorizations\PermissionSeeder;
use Database\Seeders\Authorizations\RoleSeeder;
use Database\Seeders\Authorizations\ShieldSeeder;
use Illuminate\Database\Seeder;

class AuthorizationSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            ShieldSeeder::class,
            RoleSeeder::class,
            PermissionSeeder::class,
        ]);

    }
}
