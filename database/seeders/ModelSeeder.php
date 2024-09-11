<?php

namespace Database\Seeders;

use Database\Seeders\Models\CategorySeeder;
use Database\Seeders\Models\ClientSeeder;
use Database\Seeders\Models\ItemSeeder;
use Database\Seeders\Models\OrganizationSeeder;
use Database\Seeders\Models\ProjectSeeder;
use Database\Seeders\Models\SupplierSeeder;
use Database\Seeders\Models\UserSeeder;
use Illuminate\Database\Seeder;

class ModelSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            OrganizationSeeder::class,
            UserSeeder::class,
            //            SupplierSeeder::class,
            CategorySeeder::class,
            ItemSeeder::class,
            ClientSeeder::class,
            ProjectSeeder::class,
        ]);
    }
}
