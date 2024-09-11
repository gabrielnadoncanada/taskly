<?php

namespace Database\Seeders\Models;

use App\Models\Client;
use App\Models\Organization;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run()
    {
        Organization::all()->each(function ($organization) {
            Client::factory()
                ->count(10)
                ->hasAddresses(3)
                ->create([Client::ORGANIZATION_ID => $organization->id]);
        });
    }
}
