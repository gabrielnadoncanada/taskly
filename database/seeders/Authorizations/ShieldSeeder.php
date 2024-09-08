<?php

namespace Database\Seeders\Authorizations;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class ShieldSeeder extends Seeder
{
    public function run()
    {
        Artisan::call('shield:generate --all');
    }
}
