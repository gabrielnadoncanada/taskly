<?php

namespace Database\Seeders;

use Database\Seeders\Models\CarrierSeeder;
use Database\Seeders\Models\CustomerSeeder;
use Database\Seeders\Models\OrganizationSeeder;
use Database\Seeders\Models\ReceiptSeeder;
use Database\Seeders\Models\ShipmentSeeder;
use Database\Seeders\Models\UserSeeder;
use Database\Seeders\Models\WarehouseSeeder;
use Illuminate\Database\Seeder;

class ModelSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            OrganizationSeeder::class,
            UserSeeder::class,

            CarrierSeeder::class,
            WarehouseSeeder::class,
            CustomerSeeder::class,
            ReceiptSeeder::class,
            ShipmentSeeder::class,
        ]);
    }
}
