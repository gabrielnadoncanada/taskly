<?php

namespace Database\Seeders\Models;

use App\Models\Customer;
use App\Models\Organization;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        Organization::all()->each(function ($organization) {
            Customer::factory()
                ->count(10)
                ->hasAddress(1)
                ->hasContacts(3)
                ->create([Customer::ORGANIZATION_ID => $organization->id]);
        });
    }
}
