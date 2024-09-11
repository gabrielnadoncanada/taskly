<?php

namespace Database\Seeders\Models;

use App\Models\Organization;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run()
    {
        Organization::all()->each(function ($organization) {
            Supplier::factory()
                ->count(3)
                ->hasAddresses(3)
                ->create([Supplier::ORGANIZATION_ID => $organization->id]);
        });
    }
}
