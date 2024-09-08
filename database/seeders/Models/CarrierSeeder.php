<?php

namespace Database\Seeders\Models;

use App\Models\Carrier;
use App\Models\Organization;
use Illuminate\Database\Seeder;

class CarrierSeeder extends Seeder
{
    public function run()
    {
        Organization::all()->each(function ($organization) {
            Carrier::factory()
                ->count(3)
                ->create([Carrier::ORGANIZATION_ID => $organization->id]);
        });
    }
}
