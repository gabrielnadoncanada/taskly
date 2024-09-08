<?php

namespace Database\Seeders\Models;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $organizations = Organization::all();

        $organizations->each(function ($organization) {
            User::factory(10)
                ->create()
                ->each(function ($user) use ($organization) {
                    $user->organizations()->attach($organization);
                });
        });
    }
}
