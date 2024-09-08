<?php

namespace Database\Seeders\Models;

use App\Models\Organization;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    public function run()
    {
        Organization::all()->each(function ($organization) {
            Warehouse::factory()
                ->count(5)
                ->hasAddress(1)
                ->hasLocalizations(3)
                ->create(['organization_id' => $organization->id]);
        });
    }
}
