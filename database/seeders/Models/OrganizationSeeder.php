<?php

namespace Database\Seeders\Models;

use App\Models\Organization;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    public function run()
    {
        Organization::factory(1)->create();
    }
}
